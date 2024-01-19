<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        DB::update('update employeedetailstable set isdisable = ? where userID = ?',[1,$userID]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    public function enable_employee(Request $req){
        $userID= $req->userID;
        DB::update('update employeedetailstable set isdisable = ? where userID = ?',[0,$userID]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    
    //valid employees
    public function browse_employees(Request $req){
        $pageNo=$req->pageNo;
        $user = DB::table("employeedetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('isDisable', 0)
        ->get();
       return view('backoffice.pages.browse_employees', ['employees'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1]);
    }   
    
    //both valid and in valid employees
    public function browse_all_employees(Request $req){
        $pageNo=$req->pageNo;
        $user = DB::table("employeedetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
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

}
