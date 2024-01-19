<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class serviceUserController extends Controller
{
    //
    public function addnew_serviceUser(){
        return view('backoffice.pages.addnew_serviceUser', ['user'=>'']);
    }   
    public function save_serviceUser(Request $req){
        $userID=$req->userID;

        $rules= ([
            'firstName' => 'required|max:20',
            'lastName' => 'required|max:20', 
            'postCode' => 'required|min:6|max:10',
            'mobile' =>  ['required', 'regex:/^\+44\d{7,11}$/'],
            'email' => 'required|email',
        ]);
        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $fieldSet=[
                'title'=>$req->title,
                'firstName'=>$req->firstName,
                'lastName' =>$req->lastName,
                'address' =>$req->postCode,
                'tel'=>$req->mobile,
                'proxy'=>$req->proxy,
                'companyID'=>$req->companyID,
                'email'=>$req->email
            ];
            if ($userID==-1){
                $insertStatus=DB::table('serviceuserdetailstable')->insert($fieldSet);
                if ($insertStatus==1){
                    return response()->json(['success'=>'Added new records.',
                                         'status' => 1]);
                }else{
                    return response()->json(['Error'=>'Problems with the database',
                                         'status' => -1]);
                }
            }else if ($userID >0){
                $updateStatus= DB::table('serviceuserdetailstable')
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

    public function disable_serviceUser(Request $req){
        $userID= $req->userID;
        DB::update('update serviceuserdetailstable set isdisable = ? where userID = ?',[1,$userID]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    public function enable_serviceUser(Request $req){
        $userID= $req->userID;
        DB::update('update serviceuserdetailstable set isdisable = ? where userID = ?',[0,$userID]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    

    public function browse_serviceUsers(Request $req){
        $pageNo=$req->pageNo;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('isDisable', 0)
        ->get();
       return view('backoffice.pages.browse_serviceUsers', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1]);
    }   
    
    public function browse_all_serviceUsers(Request $req){
        $pageNo=$req->pageNo;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->get();
       return view('backoffice.pages.browse_serviceUsers', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag'=> 0]);
    } 



    
    public function browse_surveyFeedback_serviceUser(Request $req){
        $result_date=formsController::set_surveyDate($req);
        $pageNo=$result_date['pageNo'];
        $dateFlag=$result_date['dateFlag'];
        $month=$result_date['month'];
        $year=$result_date['year'];
        $date=$result_date['date'];
        
        $companyID=$this->company_settings[0]->companyID;
        $resTypeID=1;
        $sendByEmail=0;
        $result=formsController::survey_status($date, "serviceuserdetailstable",$resTypeID,$companyID,$sendByEmail);

        $responseStatus=$result['responseStatus'];
        $usersDetails=$result['userDetails'];    
        return view('backoffice.pages.browse_surveyFeedback',
              ['responseStatus' => $responseStatus, 'usersDetails'=> $usersDetails,
               'isServiceUser' => 1,
               'month' => $month, 'year' => $year, 'pageNo' => $pageNo, 'dateFlag' => $dateFlag
            ]);
    }



    private function get_serviceUser_details($userID){
         $user = DB::table("serviceuserdetailstable")
         ->select('*')
         ->where(['userID'=>$userID])
         ->get();
         return $user;   
    }
   
  
    
    
    
    //updates service user from modal
    public function get_serviceUserDetails(Request $req){
        $userID=$req->userID;
        $userDetails= $this->get_serviceUser_details($userID);
        $view = (string)View::make('backoffice.inc.serviceUserFields',['user'=>$userDetails[0]]); 
        return response()->json($view); 
    }

    
    public function show_compliments_serviceUser(){
        return view('backoffice.pages.show_compliments_serviceUser');
    }
    
    public function show_complaints_serviceUser(){
        return view('backoffice.pages.show_complaints_serviceUser');
    }

}
