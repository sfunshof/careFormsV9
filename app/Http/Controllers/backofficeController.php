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


    
    public function show_feedback_dashboard(){
        
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

        return view('backoffice.pages.feedback_dashboard', $DataArray);
    }
    
    public function customRound($number) {
        // Check if the number has decimal places
        if (strpos($number, '.') !== false) {
            // Extract the decimal part
            $decimalPart = substr($number, strpos($number, '.') + 1);
    
            // If the decimal part is greater than 0, round to one decimal place
            if ((int)$decimalPart > 0) {
                return round($number, 1);
            }
        }
    
        // Otherwise, round to the nearest integer
        return round($number);
    }
    
    private function get_employee_records(){
        $companyID = null;
        if (isset($this->company_settings[0])) {
            $companyID = $this->company_settings[0]->companyID;
        } else {
            $companyID = session()->get('companyID');
        }
        // Query to get the required data
        $employeeData = DB::table('employeedetailstable')
        ->select('userID', 'firstName', 'middleName', 'lastName')
        ->where('companyID', $companyID)
        ->get();

        // Initialize an empty associative array
        $array_name = [];

        // Populate the array with concatenated names
        foreach ($employeeData as $employee) {
            $fullName = $employee->firstName . ' ' . substr($employee->middleName, 0, 1) . ' ' . $employee->lastName;
            $array_name[$employee->userID] = $fullName;
        }
        return $array_name;
    }





    //*** SPOT CHECKS   ******/

    //write a laravel query builder to  return  the number 
    //of unique field userID  where a datetime field deletedDate is null or  
    //deletedDate is less than the current date - N  
    //N is number of months : table is called usertable
    private function get_spotcheck_users($userTable, $N){
        $companyID = null;
        if (isset($this->company_settings[0])) {
            $companyID = $this->company_settings[0]->companyID;
        } else {
            $companyID = session()->get('companyID');
        }
        
        $whereArray=[
           'companyID'=>$companyID,
           'jobFunction' => 0
        ];
        if ($userTable =="serviceuserdetailstable"){
            $whereArray=[
                'companyID'=>$companyID
            ];
        };
        
        $uniqueUserCount = DB::table($userTable)
            ->select(DB::raw('COUNT(DISTINCT userID) as unique_user_count'))
            ->where($whereArray)
            ->where(function ($query) use ($N) {
                $query->whereNull('deletedDate')
                    ->orWhere('deletedDate', '<', DB::raw("DATE_SUB(NOW(), INTERVAL $N MONTH)"));
            })
        ->get();
        //dd( $uniqueUserCount[0]->unique_user_count);
        return $uniqueUserCount[0]->unique_user_count;
    }


    private function get_spotcheck_response_users($userID, $N){
        $companyID = null;
        if (isset($this->company_settings[0])) {
            $companyID = $this->company_settings[0]->companyID;
        } else {
            $companyID = session()->get('companyID');
        }
        $uniqueUserCount = DB::table('responsetable_spotcheck')
        ->where('date_issue', '>=', now()->subMonths($N))
        ->where('companyID', $companyID) // Additional condition for companyID
        ->distinct($userID) // Get distinct carerIDs
        ->count();
        return $uniqueUserCount;
    }
    
    private function get_spotcheck_review($N){
        $companyID = null;
        if (isset($this->company_settings[0])) {
            $companyID = $this->company_settings[0]->companyID;
        } else {
            $companyID = session()->get('companyID');
        }
        // Initialize an array to store star counts
        $array_star = [];
        // Query to get star counts
        for ($i = 1; $i <= 5; $i++) {
            $starCount = DB::table('responsetable_spotcheck')
                ->where('date_issue', '>=', now()->subMonths($N))
                ->where('companyID', $companyID)
                ->where('star', $i) // Filter by star value
                ->count();

            // Store the count in the array
            $array_star[] = $starCount;
        }
        return  $array_star;
    }
    
    private function get_spotcheck_dashboard_table_data($N){
        $companyID = null;
        if (isset($this->company_settings[0])) {
            $companyID = $this->company_settings[0]->companyID;
        } else {
            $companyID = session()->get('companyID');
        }
        // Query to get the desired data
        $carerData = DB::table('responsetable_spotcheck')
        ->select(
            'carerID',
            DB::raw('COUNT(*) as countN'),
            DB::raw('MAX(DATE(date_issue)) as latest_date'),
            DB::raw('AVG(star) as rating')
        )
        ->where('date_issue', '>=', now()->subMonths($N))
        ->where('companyID', $companyID)
        ->groupBy('carerID')
        ->orderBy('latest_date', 'desc') // Orde
        ->get();
        foreach ($carerData as $data) {
            $data->rating = $this->customRound($data->rating); //     is_int($data->rating) ? $data->rating : number_format($data->rating, 1);
        }
       return $carerData;    
    }
    
    private function init_spotcheck_dashboard($Mnth){
        $total_carers=$this->get_spotcheck_users("employeedetailstable", $Mnth);
        $total_serviceUsers=$this->get_spotcheck_users("serviceuserdetailstable", $Mnth);
        $unique_carerCount=$this->get_spotcheck_response_users('carerID',$Mnth);
        $unique_serviceUserCount=$this->get_spotcheck_response_users('serviceUserID',$Mnth);
        $review=$this->get_spotcheck_review($Mnth);
        $data=$this->get_spotcheck_dashboard_table_data($Mnth);

        $users['review']=$review;  
        $users['total_carers']=$total_carers;
        $users['total_serviceUsers']=$total_serviceUsers;
        $users['unique_carers']=$unique_carerCount;
        $users['unique_serviceUsers']=$unique_serviceUserCount;
        $users['data']=$data;
        return $users;
    }

    private function get_spotcheck_dashboard_data($Mnth){
        //go into database table to get the review per star
        //select sum(*) groupby star
        $spotcheck_users=$this->init_spotcheck_dashboard($Mnth);
        //dd($spotcheck_users['review']);
        $employee_records=$this->get_employee_records();

        $array_star =$spotcheck_users['review'];       //[3, 5, 10, 7, 9];
        $totalRatings = array_sum($array_star);
        $weightedScore = 0;
        for ($i = 0; $i < count($array_star); $i++) {
                $weightedScore += (($i + 1) * $array_star[$i]);
        }
        $weightedScoreOutOf5=0;
        if ($totalRatings > 0){
             $weightedScoreOutOf5= $this->customRound(($weightedScore / ($totalRatings * 5)) * 5);
        }
        $dataArray=[
            'total_carers' => $spotcheck_users['total_carers'],
            'total_serviceUsers' => $spotcheck_users['total_serviceUsers'],
            'unique_carers' => $spotcheck_users['unique_carers'],
            'unique_serviceUsers' => $spotcheck_users['unique_serviceUsers'],
            'records' => $spotcheck_users['data'],
            'array_star' =>$array_star,
            'total_star' =>$totalRatings,
            'employee_data' =>$employee_records,
            'selected' => $Mnth,
            'weighted_star' => $weightedScoreOutOf5
        ]; 
        return $dataArray;
    }

    public function show_spotcheck_dashboard(){
        $dataArray=$this->get_spotcheck_dashboard_data(3);
        return view('backoffice.pages.spotcheck_dashboard', $dataArray);
    }
    
    public function update_spotcheck_dashboard(Request $req){
        $selectMnth=$req->selectedMnth;
        $dataArray=$this->get_spotcheck_dashboard_data($selectMnth);
        return view('backoffice.pages.spotcheck_component_dashboard', $dataArray);        
    }

    
      
    //copied but modified from employeeController: spot Checked
    private function get_all_valid_spotcheck_employees(){
        $companyID = session()->get('companyID');
        // Query to get the required data
        $employee_spotCheckData = DB::table('employeedetailstable')
        ->select('userID')
        ->where('companyID', $companyID)
        ->where('jobFunction', 0)
        ->where('isDisable', 0)
        ->get();
        return  $employee_spotCheckData;
    }
    private function get_all_carerNames(){
        $companyID = session()->get('companyID');
        // Query to get the required data
        $employeeData = DB::table('employeedetailstable')
        ->select('userID', 'firstName', 'middleName', 'lastName')
        ->where('companyID', $companyID)
        ->get();

        // Initialize an empty associative array
        $array_name = [];
        // Populate the array with concatenated names
        foreach ($employeeData as $employee) {
            $fullName = $employee->firstName . ' ' . substr($employee->middleName, 0, 1) . ' ' . $employee->lastName;
            $array_name[$employee->userID] = $fullName;
        }
        return $array_name;
    }
    private function get_all_employees_not_spotchecked($spotcheck, $pure){
        // Extracting the userID values from the $spotcheck array
        $spotcheckUserIDs = collect($spotcheck)->pluck('carerID')->unique();
        // Extracting the userID values from the $pure array
        $pureUserIDs = collect($pure)->pluck('userID');
        // Calculating the difference between $pureUserIDs and $spotcheckUserIDs
        $diff = $pureUserIDs->diff($spotcheckUserIDs);
        //dd($diff->values()->all());
        return $diff->values()->all();
    }
    
    //This is called externally by mobilespotcheckController
    //to intitally populate the modal reports
    private function init_mobile_spotcheck($mnth){
        $allValid=$this->get_all_valid_spotcheck_employees();
        $dataArray=$this->get_spotcheck_dashboard_data($mnth);
        $unsedCarerIDs=$this->get_all_employees_not_spotchecked($dataArray['records'],$allValid);    
        dd($dataArray);
        $dataArray['not_yet_spotCheckedIDs']=$unsedCarerIDs;
        $dataArray['carerNames']=$this->get_all_carerNames();
        return  $dataArray;
    }
    public function get_mobile_spotcheck_data(){
        $dataArray=$this->init_mobile_spotcheck(3);
        return $dataArray;
    }
    //This is also used by the mobile spot check     
    public function show_mobile_spotcheck_data(Request $req){
        $selectMnth=$req->selectedMnth;
        $dataArray=$this->init_mobile_spotcheck($selectMnth);
        return view('mobilespotcheck.fakecomponents.reportTable', $dataArray);
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
