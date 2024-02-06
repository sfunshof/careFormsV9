<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class backofficeController extends Controller
{
    
    //
    private function countElements($array) {
        $result = array();
        foreach ($array as $value) {
            if (array_key_exists($value, $result)) {
                $result[$value]++;
            } else {
                $result[$value] = 1;
            }
        }
        return $result;
    }
    
    private function get_dashboardDate($date){
        $date = Carbon::parse($date);
        $formattedDate = $date->format('F Y');
        return $formattedDate; // Output: April 2023
    }
    
    private function get_employee_or_serviceUserDates($year, $type){
        $companyID=$this->company_settings[0]->companyID;
        $dates = DB::table("responsetable")
        ->selectRaw('date_of_interest as date')
        ->where(['companyID'=> $companyID,'responseTypeID' => $type])
        ->whereDate('date_of_interest', '>', $year)
        ->orderByDesc('date_of_interest')
        ->groupBy('date_of_interest')
        ->get();
        return $dates;
    }

    private function get_employee_or_serviceUserVariables($date,$type){
        $companyID=$this->company_settings[0]->companyID;
        $postedCount = DB::table("responsetable")
            ->selectRaw('count(*) as countX')
            ->where(['companyID'=> $companyID,'responseTypeID' => $type ])
            ->whereDate('date_of_interest', $date)
            ->get();

        $respCount = DB::table("responsetable")
            ->selectRaw('count(*) as countX')
            ->where(['companyID'=> $companyID,'responseTypeID' => $type ])
            ->whereDate('date_of_interest', $date)
            ->whereNotNull('date_received')
            ->get();
        
        //responses produce Yes, Yes, No, Maybe, No for each 2023-03-01
        $responses = DB::table("responsetable")
            ->select(DB::raw("COALESCE(responses, '[]') as responses"), 'quesName', 'CQCid','quesTypeID','quesOptions')
            ->where(['companyID'=> $companyID,'responseTypeID' => $type ])
            ->whereDate('date_of_interest', $date)
            ->get();
        $result['postedCount']=$postedCount;
        $result['respCount']=$respCount;
        $result['responses']=$responses;
        return $result;    
    }
    
    private function get_employee_or_serviceUserMainDetails($past_3years,  $cqcArray,  $type){
        //get the valid dates, quesNames CQC, types and options. 
        //Only dates is multiple ie different The others are single that is the same throughout
        //for that particular date, the options, cQc etc were repeated
        $companyID=$this->company_settings[0]->companyID;
        $employeeDates= $this->get_employee_or_serviceUserDates($past_3years, $type);
        
        $responseKeyArray=[];
        $responseValueArray=[];
        $quesNameArray=[];
        $CQCArray=[];
        $quesTypeIDArray=[];
        $quesOptionsArray=[]; 
        $respCountArray=[];
        $postedCountArray=[];
       //Save the dates into chartDateArray  
        $chartDateArray=[];    
        foreach($employeeDates as $employeeDate){
            //define these variables
            $responseKeyArray[$employeeDate->date]=[];
            $responseValueArray[$employeeDate->date]=[];
            $quesNameArray[$employeeDate->date]=[];
            $CQCArray[$employeeDate->date]=[];;
            $quesTypeIDArray[$employeeDate->date]=[];
            $quesOptionsArray[$employeeDate->date]=[]; 
            $respCountArray[$employeeDate->date]=[];
            $postedCountArray[$employeeDate->date]=[];
            array_push($chartDateArray, $employeeDate->date);
        }
        //** End of variable definiation  */
    
        //If the first response is null then we have a problem, therefore we use 
        //quesName from table
        


        //** For each date get the values */
        //$responseKeyArray=[];   //"yes", "no","maybe"
        //$responseValueArray=[]; //0,2,4
        
        //$employeeDates=[2023-12-01, 2023-11-01];

        foreach ($employeeDates as $employeeDate) {
             //put here 
            $result=$this->get_employee_or_serviceUserVariables($employeeDate->date,$type);
            
            $postedCount= $result['postedCount']; //0
            $respCount=$result['respCount']; //This is the response for each date: 0, 1, 10 20 etc
            $responses= $result['responses']; // All responses  yes, no for each employee
            
            $responseArray=[];
            // No longer used if there are no reponses we get error what we want is the no of questions
            //we can assume 5 max but lets use 500
            //$respArray=json_decode($responses[0]->responses); //[0] b/c it is same for each eamployee AT THAT TIME!!!
            
            $noOfResp  = 500 ; // No of employees or service users   is_array($respArray) ? count($respArray) : 0 ; //[yes, no, excellent]==3
            for ($i=0; $i<$noOfResp;$i++){ //c
                $responseArray[$i]=[];
                $outputArray[$i]=[];
            }
            //responses => responses: [yes, no], quesName: how many

            $quesNames=null;
            $quesTypeIDs=null;
            $cqcIDs=null;
            $quesOptions=null;
            foreach($responses as $response){ // for each employee or service user's responses ["Yes", "No", "Maybe"]
                $respArray=json_decode($response->responses); // converts '["yes", "No"] into proper array 
                $i=0;
                if (!is_null( $respArray)){
                    foreach($respArray as $resp){ //Now each Yes, No May is saved into diffrent array
                        $respT=$resp;
                        if (str_contains($resp, "Others<br>")){
                        $respT="Others";
                        }
                        array_push($responseArray[$i], $respT); // respArray[1]=ques1, respArray[2]=ques2
                        $i++;
                    }
                }
                //This is to stop the use of response[0]->whatevere
                //This is because it may be null when it has not yet been filled by the service user
                //Howvere if at least one is filled, then we use that one
                if ($response->quesName){
                    $quesNames= $response->quesName;
                }
                if ($response->quesTypeID){
                    $quesTypeIDs=$response->quesTypeID;
                }
                if ($response->CQCid){
                    $cqcIDs= $response->CQCid;
                }
                if ($response->quesOptions){
                    $quesOptions=$response->quesOptions;
                }

            }
     
            //1st ques => responseArray[1]=[Yes1, No2, Yes3,No4]       ==>  outputArray[1]=[Yes=>2,No=>2]
            //2nd ques => responseArray[2]=[No1,  Yes2, Yes3,Maybe4]   ==>  outputArray[2]=[Yes=>2, No=>1, Maybe=>1]
            for($i=0;$i<sizeof($responseArray); $i++){
                $outputArray[$i]=$this->countElements($responseArray[$i]); //Yes=>2, N0=>2, Maybe->1
            }
             
            //split this
            for($i=0;$i<sizeof($responseArray); $i++){
                array_push($responseKeyArray[$employeeDate->date],array_keys($outputArray[$i]));
                array_push($responseValueArray[$employeeDate->date], array_values($outputArray[$i]));
            }
        
            //$responseKeyArray[2023-02-01]=[Yes,No], [Yes,No], [Yes,No, Maybe]    
            //$responseValueArray[2023-02-01]=[3,4], [5,2], [3,1, 1]
            //                                 ques1  ques2   ques3   
            
            $respCountArray[$employeeDate->date]=$respCount[0]->countX;
            $postedCountArray[$employeeDate->date]=$postedCount[0]->countX;
            $quesNameArray[$employeeDate->date]=json_decode($quesNames);              //($responses[0]->quesName);
            $quesTypeIDArray[$employeeDate->date]=json_decode($quesTypeIDs);            // ($responses[0]->quesTypeID);
            if ($quesTypeIDArray[$employeeDate->date]==null) $quesTypeIDArray[$employeeDate->date]=[];
            $CQCArray[$employeeDate->date]=json_decode($cqcIDs);                //$responses[0]->CQCid);
            $quesOptionsArray[$employeeDate->date]= json_decode($quesOptions);      //$responses[0]->quesOptions);
           
        }
       
        //** Get the latest for the date-of_interest Month and Year  */
        
        //** End of latest month and year  */
        $mnth ="";
        $yr = "";
        $mnth_prev ="";
        $yr_prev = "";
        $dashboard_date='';
        $dashboard_date_prev='';
        if (isset( $chartDateArray[0])) {
            // $array is defined
            $latestDate=$chartDateArray[0];
            $date = Carbon::parse($latestDate);
            $mnth = $date->month;
            $yr = $date->year;
            $dashboard_date=$this->get_dashboardDate($latestDate);
        } else {
            // $array is not defined
        }
        if (isset( $chartDateArray[1])) {
            // $array is defined
            $prevDate=$chartDateArray[1];
            $date = Carbon::parse($prevDate);
            $mnth_prev = $date->month;
            $yr_prev = $date->year;
            $dashboard_date_prev=$this->get_dashboardDate($prevDate);

        } else {
            // $array is not defined
        } 
        $result['MnNo'] = $mnth; //starting month no
        $result['YrNo'] = $yr;   //starting year
        $result['chartDateArray'] = $chartDateArray; // 0=>2023-01-01 1=>2023-03-01
        $result['dashboard_date'] = $dashboard_date;
        $result['dashboard_date_prev'] = $dashboard_date_prev;
        $result['respCountArray']= $respCountArray;
        $result['postedCountArray']= $postedCountArray;
        $result['quesOptions'] = $quesOptionsArray; //[yes,No], [excellent, good, bad], 
        $result['CQCArray']=  $cqcArray;
        $result['CQCids'] = $CQCArray;
        $result['quesTypeIDs'] = $quesTypeIDArray; 
        $result['quesNames'] = $quesNameArray;  /* This is repetition , simply take the 1st */
        $result['employeeDates'] = $employeeDates;          /* 2023-02-01, 2023-03-01, 2023-04-01 */
        $result['responseKeyArray']  = $responseKeyArray;   /*[Yes,No,maybe] */
        $result['responseValueArray'] = $responseValueArray;     
        
        return $result;
    }
    
    private function get_response_per_date($type){
        $threeYearsAgo = now()->subYears(3);
        $results = DB::table('responsetable')
            ->select('date_of_interest', 'quesoptions')
            ->whereNotNull('date_of_interest')
            ->where('responseTypeID', $type)
            ->whereNotNull('quesOptions')
            ->where('date_of_interest', '>=', $threeYearsAgo)
            ->groupBy('date_of_interest', 'quesoptions')
            ->get();
             // Create an associative array with date_of_interest as keys 
             //and quesoptions as values
            $associativeArray = [];
            foreach ($results as $result) {
                $associativeArray[$result->date_of_interest] = $result->quesoptions;
            }
            return $associativeArray;
    }


    
    public function show_dashboard(){
        
        $years = collect(range(2, 0))->map(function ($year) {
            return \Carbon\Carbon::now()->subYears($year)->year;
        })->toArray();
        //$yesars => 2023,2022,2021

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate(null, $i, 1)->format('F');
        }
        //($months); January , Februrary, March
      
        $CQCnames = DB::table("cqctable")
            ->select('*')
            ->get();
        $cqc_array_pure=[];    
        foreach ($CQCnames as $CQCname){
            $cqc_array_pure[$CQCname->CQCid]=$CQCname->CQCtext;
        };
        //CQCnames[0] ** not applicab;e [1] => leadership
        //3years ago
        $past_3years = Carbon::now()->subYears(3)->format('Y-01-01'); //2021-01-01
        
        
        $result_emp=$this->get_employee_or_serviceUserMainDetails($past_3years, $cqc_array_pure, 2);
        $mnth_emp=$result_emp['MnNo']; //starting month no
        $yr_emp=$result_emp['YrNo'];
        $chartDateArray_emp=$result_emp['chartDateArray']; // 0=>2023-01-01 1=>2023-03-01
        $dashboard_date_emp=$result_emp['dashboard_date'];
        $dashboard_date_prev_emp= $result_emp['dashboard_date_prev'];
        $respCountArray_emp= $result_emp['respCountArray'];
        $postedCountArray_emp=$result_emp['postedCountArray'];
        $quesOptionsArray_emp=$result_emp['quesOptions']; //[yes,No], [excellent, good, bad], 
        $CQCArray_emp=$result_emp['CQCids'];
        $quesTypeIDArray_emp=$result_emp['quesTypeIDs']; 
        $quesNameArray_emp=$result_emp['quesNames'];  /* This is repetition , simply take the 1st */
        $employeeDates_emp=$result_emp['employeeDates'];          /* 2023-02-01, 2023-03-01, 2023-04-01 */
        $responseKeyArray_emp=$result_emp['responseKeyArray'];   /*[Yes,No,maybe] */
        $responseValueArray_emp= $result_emp['responseValueArray']; 
        $response_per_date_emp=$this->get_response_per_date(2);
        

        $result_su=$this->get_employee_or_serviceUserMainDetails($past_3years, $cqc_array_pure, 1);
        $mnth_su=$result_su['MnNo']; //starting month no
        $yr_su=$result_su['YrNo'];
        $chartDateArray_su=$result_su['chartDateArray']; // 0=>2023-01-01 1=>2023-03-01
        $dashboard_date_su=$result_su['dashboard_date'];
        $dashboard_date_prev_su= $result_su['dashboard_date_prev'];
        $respCountArray_su= $result_su['respCountArray'];
        $postedCountArray_su=$result_su['postedCountArray'];
        $quesOptionsArray_su=$result_su['quesOptions']; //[yes,No], [excellent, good, bad], 
        $CQCArray_su=$result_su['CQCids'];
        $quesTypeIDArray_su=$result_su['quesTypeIDs']; 
        $quesNameArray_su=$result_su['quesNames'];  // This is repetition , simply take the 1st 
        $employeeDates_su=$result_su['employeeDates'];          // 2023-02-01, 2023-03-01, 2023-04-01 
        $responseKeyArray_su=$result_su['responseKeyArray'];   // [Yes,No,maybe] 
        $responseValueArray_su= $result_su['responseValueArray']; 
        $response_per_date_su=$this->get_response_per_date(1);
               
        $DataArray=[
            'years' => $years,
            'months' => $months, 
            'CQCArray'=>  $cqc_array_pure, 
            'MnNo_emp' => $mnth_emp,
            'YrNo_emp' => $yr_emp,   
            'chartDateArray_emp' => $chartDateArray_emp, // 0=>2023-01-01 1=>2023-03-01
            'dashboard_date_emp' => $dashboard_date_emp,
            'dashboard_date_prev_emp' =>$dashboard_date_prev_emp,
            'respCountArray_emp'=> $respCountArray_emp,
            'postedCountArray_emp'=> $postedCountArray_emp,
            'quesOptions_emp' => $quesOptionsArray_emp, //[yes,No], [excellent, good, bad], 
            'CQCids_emp' => $CQCArray_emp, //based on entered build form
            'quesTypeIDs_emp' => $quesTypeIDArray_emp, 
            'quesNames_emp' => $quesNameArray_emp,  /* This is repetition , simply take the 1st */
            'employeeDates_emp' => $employeeDates_emp,          /* 2023-02-01, 2023-03-01, 2023-04-01 */
            'responseKeyArray_emp'  => $responseKeyArray_emp,   /*[Yes,No,maybe] */
            'responseValueArray_emp' => $responseValueArray_emp, /*[2,1,0] */
            'response_per_date_emp' => $response_per_date_emp, //2023-12-01 => [[yes, No], [Maybe, No]] 2023-11-01 => [[yes, No], [Maybe, No]]
            'MnNo_su' => $mnth_su,
            'YrNo_su' => $yr_su,   
            'chartDateArray_su' => $chartDateArray_su, // 0=>2023-01-01 1=>2023-03-01
            'dashboard_date_su' => $dashboard_date_su,
            'dashboard_date_prev_su' =>$dashboard_date_prev_su,
            'respCountArray_su'=> $respCountArray_su,
            'postedCountArray_su'=> $postedCountArray_su,
            'quesOptions_su' => $quesOptionsArray_su, //[yes,No], [excellent, good, bad], 
            'CQCids_su' => $CQCArray_su, //based on entered build form
            'quesTypeIDs_su' => $quesTypeIDArray_su, 
            'quesNames_su' => $quesNameArray_su,  /* This is repetition , simply take the 1st */
            'employeeDates_su' => $employeeDates_su,          /* 2023-02-01, 2023-03-01, 2023-04-01 */
            'responseKeyArray_su'  => $responseKeyArray_su,   /*[Yes,No,maybe] */
            'responseValueArray_su' => $responseValueArray_su, /*[2,1,0] */
            'response_per_date_su' => $response_per_date_su //2023-12-01 => [[yes, No], [Maybe, No]] 2023-11-01 => [[yes, No], [Maybe, No]]
        ];
        
        //dd($DataArray);

        return view('backoffice.pages.dashboard', $DataArray);
    }

    public function show_companyProfile(){
        $companyID=$this->company_settings[0]->companyID;
        $companyProfile = DB::table("companyprofiletable")
        ->select("*")
        ->where('companyID', $companyID)
        ->get();
        return view('backoffice.pages.update_companyProfile', ['companyProfile'=>$companyProfile[0]]);
    }

    public function update_companyProfile(Request $req){
        $rules= ([
            'companyName' => 'required|max:30',
            'contactEmail' => 'required|email', 
            'smsName' => 'required|max:11',
            'smsPreTextEmp' => 'required|max:245',
            'smsPreTextSu' => 'required|max:245',
        ]);
        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $fieldSet=[
                'companyName'=>$req->companyName,
                'contactEmail'=>$req->contactEmail,
                'smsName' =>$req->smsName,
                'smsPreTextEmp' =>$req->smsPreTextEmp,
                'smsPreTextSu' =>$req->smsPreTextSu,
            ];
             $updateStatus= DB::table('companyprofiletable')
                ->where('companyID', $this->company_settings[0]->companyID)
                ->update($fieldSet);
                return response()->json(['success'=>'Updated records.',
                'status' => 1]);
        }
        //validation failure
        return response()->json([
              'error'=>$validator->errors(),
              'status'=>0
               ]);
    }

}
