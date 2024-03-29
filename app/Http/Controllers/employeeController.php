<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class employeeController extends Controller
{
    //
    public function addnew_employee(){
        return view('backoffice.pages.addnew_employee', ['user'=>'']);
    }   
    public function addnew_serviceUser(){
        return view('backoffice.pages.addnew_serviceUser', ['user'=>'']);
    }   
    public function save_employee(Request $req){
        $userID=$req->userID;

        $rules= ([
            'firstName' => 'required|max:30',
            'lastName' => 'required|max:40', 
            'mobile' =>  ['required', 'regex:/^\+44\d{7,11}$/'],
            'email' => 'required|email'
        ]);
        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $fieldSet=[
                'firstName'=>$req->firstName,
                'lastName' =>$req->lastName,
                'email' =>$req->email,
                'tel'=>$req->mobile,
                'jobFunction'=>$req->job,
                'companyID'=>$req->companyID
            ];
            if ($userID==-1){
                $insertStatus=DB::table('employeedetailstable')->insert($fieldSet);
                if ($insertStatus==1){
                    return response()->json(['success'=>'Added new records.',
                                         'status' => 1]);
                }else{
                    return response()->json(['Error'=>'Problems with the database',
                                         'status' => -1]);
                }
            }else if ($userID >0){
                $updateStatus= DB::table('employeedetailstable')
                ->where('userID', $userID)
                ->update($fieldSet);
                return response()->json(['success'=>'Updated records.',
                'status' => 1]);
            }
            
        }
        //validation failure
        return response()->json([
              'error'=>$validator->errors(),
              'status'=>0
               ]);
    }
    public function disable_employee(Request $req){
        $userID= $req->userID;
        //DB::update('update employeedetailstable set isdisable = ? where userID = ?',[1,$userID]);
        DB::table('employeedetailstable')
            ->where('userID', $userID)
            ->update([
                'isdisable' => 1,
                'deletedDate' => Carbon::now()
            ]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    public function enable_employee(Request $req){
        $userID= $req->userID;
        //DB::update('update employeedetailstable set isdisable = ? where userID = ?',[0,$userID]);
        DB::table('employeedetailstable')
        ->where('userID', $userID)
        ->update([
            'isdisable' => 0,
            'deletedDate' => null,
        ]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    
    //valid employees
    public function browse_employees(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("employeedetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('isDisable', 0)
        ->where('companyID', $companyID)
        ->get();
       return view('backoffice.pages.browse_employees', ['employees'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1]);
    }   
    
    //both valid and in valid employees
    public function browse_all_employees(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("employeedetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('companyID', $companyID)
        ->get();
       return view('backoffice.pages.browse_employees', ['employees'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag'=> 0]);
    } 
    
    //updates employe from modal
    public function get_employeeDetails(Request $req){
        $userID=$req->userID;
        $userDetails= $this->get_employee_details($userID);
        $view = (string)View::make('backoffice.inc.employeeFields',['user'=>$userDetails[0]]); 
        return response()->json($view); 
    }

    private function get_employee_details($userID){
        $user = DB::table("employeedetailstable")
        ->select('*')
        ->where(['userID'=>$userID])
        ->get();
        return $user;   
   }

    public function browse_surveyFeedback_employee(Request $req){
        $result_date=formsController::set_surveyDate($req);
        $pageNo=$result_date['pageNo'];
        $dateFlag=$result_date['dateFlag'];
        $month=$result_date['month'];
        $year=$result_date['year'];
        $date=$result_date['date'];
        
        $companyID=$this->company_settings[0]->companyID;
        $resTypeID=2;
        $sendByEmail=1;
        $result=formsController::survey_status($date, "employeedetailstable",$resTypeID,$companyID,$sendByEmail);
        $responseStatus=$result['responseStatus'];
        $usersDetails=$result['userDetails'];    
        return view('backoffice.pages.browse_surveyFeedback',
            ['responseStatus' => $responseStatus, 'usersDetails'=> $usersDetails,
            'isServiceUser' => 0,
            'month' => $month, 'year' => $year, 'pageNo' => $pageNo, 'dateFlag' => $dateFlag
            ]);
    }
    
    private function get_user_records($userTable){
        $companyID=$this->company_settings[0]->companyID;
        // Query to get the required data
        $employeeData = DB::table($userTable)
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

       
    /***** SPOT CHECK ******/
    //This gets all the employee records irrespective of deleted or job function
    private function get_all_carerNames(){
        $companyID=$this->company_settings[0]->companyID;
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
    private function get_all_serviceUserNames(){
        $companyID=$this->company_settings[0]->companyID;
        $userDetails = DB::table('serviceuserdetailstable')
            ->select('userID', DB::raw("CONCAT(title, ' ', firstName, ' ', lastName) AS full_name"))
            ->where('companyID', $companyID)
            ->get()
            ->pluck('full_name', 'userID')
            ->toArray();
        return $userDetails;    
    }
    private function get_all_valid_spotcheck_employees(){
        $companyID=$this->company_settings[0]->companyID;
        // Query to get the required data
        $employee_spotCheckData = DB::table('employeedetailstable')
        ->select('userID')
        ->where('companyID', $companyID)
        ->where('jobFunction', 0)
        ->where('isDisable', 0)
        ->get();
        return  $employee_spotCheckData;
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

    private function get_spotcheck_record($N){
        $companyID=$this->company_settings[0]->companyID;
        $startDate = now()->subMonths($N)->toDateString();
        
        $uniqueCarerIDs = DB::table('responsetable_spotcheck')
        ->select( 'carerID', DB::raw('COUNT(*) as count'))
        ->where('companyID', $companyID)
        ->whereDate('date_issue', '>=', $startDate)
        ->groupBy('carerID')
        ->pluck('count', 'carerID')
        ->toArray();
                 
        $records = DB::table('responsetable_spotcheck')
            ->selectRaw("DATE(date_issue) as date, keyID, carerID, serviceUserID, star")
            ->where('companyID', $companyID)
            ->whereDate('date_issue', '>=', $startDate)
            ->get();

        $allValid=$this->get_all_valid_spotcheck_employees();
        $unsedCarerIDs=$this->get_all_employees_not_spotchecked($records,$allValid);    
        $data['selected']= $N;
        $data['table_records']=$records;
        $data['count_carerID']=$uniqueCarerIDs;
        $data['not_yet_spotCheckedIDs']=$unsedCarerIDs;
        $data['carerNames']=$this->get_all_carerNames();
        $data['serviceUserNames']=$this->get_all_serviceUserNames();
        return $data;    
    }
    
    private function getCompanyName(){
        $companyID=$this->company_settings[0]->companyID;        
        // Perform the query
        $company = DB::table('companyprofiletable')
            ->select('companyName')
            ->where('companyID', $companyID)
            ->first();
        // Extract the company name
        if ($company) {
            return $company->companyName;
        } else {
            return null;
        }
    }

    private function getCarerName($carerID) {
        $carerName = DB::table('employeedetailstable')
            ->select(DB::raw("CONCAT_WS(' ', firstName, COALESCE(SUBSTRING_INDEX(middleName, ' ', 1), ''), lastName) AS carerName"))
            ->where('userID', $carerID)
            ->first();
         return $carerName ? trim($carerName->carerName) : null;
    }
    private function getServiceUserName($serviceUserID){
        // Perform the query
        $serviceUserName = DB::table('serviceuserdetailstable')
            ->select(DB::raw("CONCAT(title, ' ', firstName, ' ', lastName) AS serviceUserName"))
            ->where('userID', $serviceUserID)
            ->first();
            return $serviceUserName ? $serviceUserName->serviceUserName : null; 
    }
    
    //This display data function is shared by the view and print pdf
    private function display_spotcheckData($keyID){
        // Perform the query
        $data = DB::table('responsetable_spotcheck')
            ->select( 'carerID', 'supervisor', 'serviceUserID', 'date_issue', 'responses', 'quesNames', 'quesTypeID', 'quesOptions', 'star')
            ->where('keyID', $keyID)
            ->get();

        // Initialize arrays to store each field
        $responses_array = [];
        $quesNames_array = [];
        $quesTypeID_array = [];
        $quesOptions_array = [];
    

        // Extract data into respective arrays
        foreach ($data as $item) {
            //[ ["Yes"], [ "Others:","Others: Unless"],["OK"],["Good"], []]
            $responses_array = json_decode($item->responses,true);
            //["Spot1Yesno",  "Spot2Yesnoothers", "Spot3Text", "Spot4excellent, good fair poor", "Spot Info" ]
            $quesNames_array = json_decode($item->quesNames,true);
            //[ 2, 2, 1,2, 0]
            $quesTypeID_array = json_decode($item->quesTypeID,true);
            //[ 
            //    "[\"Yes\",\"No\"]",  "[\"Yes\",\"No\",\"Others\"]", "[]",  "[\"Excellent\",\"Good\",\"Fair\",\"Poor\"]",  "[]"
            //]            
            $quesOptions_array =json_decode($item->quesOptions,true);
        }
             
        
        //Get the companyName
        $companyName=$this->getCompanyName();        
        //Get the supervisor
        $supervisor=$data[0]->supervisor;
        //Carer
        $carerName=$this->getCarerName($data[0]->carerID);
        //Service user
        $serviceUserName=$this->getServiceUserName($data[0]->serviceUserID);
        //get the date
        $date_issue=$data[0]->date_issue;
        $rating=$data[0]->star;
        $array=[
            'companyName' => $companyName,
            'carerName'=> $carerName,
            'serviceUserName' => $serviceUserName,
            'supervisor' => $supervisor,
            'date_issue'=> $date_issue,
            'rating' => $rating,
            'count'=> count($quesTypeID_array),
            'responses_array' => $responses_array,
            'quesNames_array' => $quesNames_array,
            'quesTypeID_array' => $quesTypeID_array,
            'quesOptions_array' => $quesOptions_array,
            'pdf_print' => 0

        ];
        return $array;
    }
    
    public function view_employee_spotCheck(Request $req){
        $keyID=$req->keyID;
        //We put this in a session so thath print pdf can use it
        //print pdf is a GET
        session(['keyID' => $keyID]);
        $array=$this->display_spotcheckData($keyID);
        return view('backoffice.pages.view_employee_spotCheck',$array);       
    }

    public function browse_employee_spotCheck(){
        $spotCheckData=$this->get_spotcheck_record(3);
        return view('backoffice.pages.browse_employee_spotCheck',$spotCheckData);
    }
    
    public function update_employee_spotcheck(Request $req){
        $selectMnth=$req->selectedMnth;
        $spotCheckData=$this->get_spotcheck_record($selectMnth);
        return view('backoffice.pages.browse_employee_spotCheck_component', $spotCheckData);        
    }
    public function pdf_employee_spotCheck(){
        //get the keyID *** tech debt Please check for vaid session before proceeding
        $keyID = session('keyID'); //
        $pdf="";
           
        if ($keyID){
            $array=$this->display_spotcheckData($keyID);
            $array['pdf_print']=1;
            $pdf = Pdf::loadView('backoffice.pages.view_employee_spotCheck', $array);
        }else{
            $pdf = Pdf::loadView('backoffice.pages.test');
        }
        return    $pdf->stream(); // $pdf->download('spotcheck.pdf');
      //  return 1; //$pdf->stream();
   
    }
}
