<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
        //check if you updated without filling the prospect ques
        if ($userID >=0){
            //Do nothing
        }else{
            $userID=-1;
        }
        
        $COSrules=[
            'appDate'=>'required',
            'interDate' => 'required',
            'COSdate'=> 'required',
            'arrDate' =>'required',
            'DBSdate' => 'required',
            'startDate' => 'required' 
        ];
        $COSmsg=[
            'appDate.required'=> 'Application date is required',
            'interDate.required'=> 'Interview date is required',
            'COSdate.required'=> 'COS issue date is required',
            'arrDate.required'=> 'UK arrival date is required',
            'DBSdate.required'=> 'DBS issue date is required',
            'startDate.required'=> 'Contract Start date is required',
        ];    
        $rules= [
            'firstName' => 'required|max:30',
            'lastName' => 'required|max:40', 
            'mobile' =>  ['required', 'regex:/^\+44\d{7,11}$/'],
            'email' => 'required|email',
            'officePostcode' => ['required','regex:/^([A-Z]{1,2}[0-9][0-9A-Z]?\s?[0-9][A-Z]{2}|[AB][0-9][0-9]\s?[0-9][A-Z]{2})$/i']

        ];
        $msg=[
            'firstName.required'=>'First Name is required',
            'lastName.required'=>'Last Name is required',
        ];

        // Add uniqueness rule for email if it's a new user
        if ($userID==-1) {
            //$rules['email'] .= '|unique:employeedetailstable,email|unique:userstable,email';
            $rules['email'] .= '|unique:employeedetailstable,email|unique:userstable,email';
        } else { //Email does not change
            // Validation rules for updating an existing user
            //$rules['email'] .= '|unique:employeedetailstable,email,' . $userID . ',userID';
        }

        // Combine the rules and messages
        if ($req->isCOS==1){
            $rules = array_merge($rules, $COSrules);
            $msg = array_merge($msg, $COSmsg);
        }
        $validator = Validator::make($req->all(), $rules, $msg);
        $COSdates = array("appDate" => $req->appDate,
                     "interDate" =>$req->interDate,
                     "COSdate" => $req->COSdate,
                     "arrDate" => $req->arrDate,
                     "DBSdate" => $req->DBSdate,
                     "startDate" => $req->startDate
        ) ;

        if ($validator->passes()) {
            $fieldSet=[
                'firstName'=>$req->firstName,
                'lastName' =>$req->lastName,
                'email' =>$req->email,
                'tel'=>$req->mobile,
                'jobFunction'=>$req->job,
                'companyID'=>$req->companyID,
                'isCOS' => $req->isCOS,
                'COSdates' => json_encode($COSdates),
                'officePostcode' =>$req->officePostcode
            ];
            
            $loginFieldSet=[
                'email'=>$req->email,
                'is_admin' => 0,
                'companyID' => $req->companyID,
                'password' => Hash::make('password')
            ];

            if ($userID==-1){
                $insertStatus=DB::table('employeedetailstable')->insert($fieldSet);
                if ($insertStatus==1){
                    $insertStatus=DB::table('userstable')->insert($loginFieldSet);
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
        $exist= $this->if_any_disabledUsers(0);
       return view('backoffice.pages.browse_employees', ['employees'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1, 'exist' => $exist]);
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
            ->where('isProspect', 0)
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
            ->selectRaw("DATE(date_issue) as date, keyID, carerID, serviceUserID, star, checkDate, checkComments")
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
            ->select( 'carerID', 'companyID', 'supervisor', 'serviceUserID', 'date_issue', 'responses', 'quesNames', 'quesTypeID', 'quesOptions', 'star', 'checkDate','checkComments')
            ->where('keyID', $keyID)
            ->get();

        // Initialize arrays to store each field: do not be confused. each cell is json array
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
        $companyID=$data[0]->companyID;
        $companyName = DB::table('companyprofiletable') 
            ->select('companyName')
            ->where('companyID', $companyID)
            ->first();
        if ($companyName) {
            $companyName= $companyName->companyName;
        }
        
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
            'pdf_print' => 0,
            'checkDate' =>$data[0]->checkDate,
            'checkComments'=>$data[0]->checkComments

        ];
        return $array;
    }

    private function if_any_disabledUsers(){
        $recordExists = DB::table('employeedetailstable')
        ->exists();
        if ($recordExists){
            return 1;
        }else{
            return 0;
        }    
    }
    
    public function view_employee_spotCheck(Request $req){
        $keyID=$req->keyID;
        //We put this in a session so thath print pdf can use it
        //print pdf is a GET
        session(['keyID' => $keyID]);
        $array=$this->display_spotcheckData($keyID);
        return view('backoffice.pages.view_employee_spotCheck',$array);       
    }
    
    public function edit_employee_spotCheck(Request $req){
        $keyID=$req->keyID;
        //We put this in a session so thath print pdf can use it
        //print pdf is a GET
        session(['keyID' => $keyID]);
        $array=$this->display_spotcheckData($keyID);
        return view('backoffice.pages.edit_employee_spotCheck',$array);       
    }
    
    public function check_employee_spotCheck(Request $request){
         $ranNo = $request->route('ranNo');
       
        $array=[];
        $array['companyName']=""; 
        $spotcheck = DB::table('responsetable_spotcheck')
            ->select('keyID')
            ->where('randomNo', $ranNo)
            ->first();
        $keyID=-1;    
        if ($spotcheck) {
            // Access companyName and spotCheckMsg attributes
            $status= 1;
            $keyID= $spotcheck->keyID;
            $array=$this->display_spotcheckData($keyID);
        } else {
            // Handle the case where no company is found
            // ...
            $status=0;
        } 

        $array['status']=$status;
        $array['keyID']=$keyID;
        return view('backoffice.pages.check_employee_spotcheck',$array);
    }

    public function check_employee_spotCheck_save(Request $req){
        $keyID=$req->keyID;
        $comments=$req->comments;
        // Generate a random number
        $randomNo = Str::random(4);
        //$randomNo = $this->generateAlphanumericCode(4);  // Generate a random number between 

        $query = DB::table('responsetable_spotcheck')
            ->where('keyID', $keyID)
            ->update([
                'checkComments' => $comments,
                'checkDate' => now(),
                'randomNo' => $randomNo,
        ]);
        $msg= '<div class="alert alert-danger" role="alert">
                   There is a problem submitting your response. Please try again!
               </div>';
        if ($query > 0){
           $msg=  '<div class="alert alert-primary" role="alert">
                   Care giver spot check responses successfully submitted
                 </div>';   
        }
        return  $msg;
    }

    public function browse_employee_spotCheck(){
        $spotCheckData=$this->get_spotcheck_record(3);
        return view('backoffice.pages.browse_employee_spotCheck',$spotCheckData);
    }
    
    //the update here is wit the dates
    public function update_employee_spotcheck(Request $req){
        $selectMnth=$req->selectedMnth;
        $spotCheckData=$this->get_spotcheck_record($selectMnth);
        return view('backoffice.pages.browse_employee_spotCheck_component', $spotCheckData);        
    }
    
    public function save_employee_spotCheck(Request $req){
           
      $keyID=$req->keyID;
       $responses=$req->responses;
       $rating=$req->rating;
       
       $jsonResponses = json_encode($responses);
       // Update the response and star fields
        DB::table('responsetable_spotcheck')
            ->where('keyID', $keyID)
            ->update([
                'responses' => $jsonResponses,
                'star' => $rating
        ]);
        return response()->json($jsonResponses);  

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
