<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class mobileController extends Controller
{
    //
    public function index(Request $request){
        //get the service user details
        //http://west/4ew2hy54xs7

        View::share('mobile_companyName', '');
        $campaign='Unknown Campaign';
        $unique_value=$request->unique_value;
        
        //ResponseTypeID is valid; what is the csmpaign name
        $resp = DB::table("responsetable")
            ->select('*')
            ->where(['unique_value'=>$unique_value])
            ->whereNull('date_received')
            ->get();
                
        //If combination not found after tampering with Service user not found
        if (!count($resp)){
            return view('mobile.pages.userNotFound', ['userType' =>'User', 'username'=>'', 'campaign'=>$campaign, 'date_of_interest'=>''] );
        }
        

        //Hey we found a user, check if it has nt yet been submitted
        if (!is_null($resp[0]->date_received)){
            return view('mobile.pages.userAlreadyDone' , ['userType' => 'User', 'username'=>'', 'campaign' => 'Submitted' , 'date_of_interest'=>'' ]);
        }
        //Expired -- implement later
        // if ($resp[0]->date_posted, now(()) > 30)
        
        //*** Everything OK fire On */
        //Now get the details
        $userTable='';
        $responseTypeID=$resp[0]->responseTypeID;
        $campaign='';
        if ($responseTypeID==1){
            $userTable="serviceuserdetailstable";
            $userType="ServiceUser";
        }else if  ($responseTypeID==2){
            $userTable="employeedetailstable";
            $userType="Employee";
        } 
        $quesFormTable="buildformtable";
        $quesFormPage="mobile.pages.mobileFeedback";  
        //ResponseTypeID is valid; what is the csmpaign name
        $respX = DB::table("responsetypetable")
         ->select('*')
         ->where(['responseTypeiD'=>$responseTypeID])
         ->get();
        if (count($respX)) {
             $campaign=$respX[0]->responseType;
        }   
       
        $user = DB::table($userTable)
        ->select('*')
        ->where(['useriD'=>$resp[0]->userID])
        ->get();
        //If db is hacked or corrupted
        if (!count($user)){
            return view('mobile.pages.userNotFound', ['userType' => 'User ', 'username'=>'', 'campaign'=>'', 'date_of_interest'=>''] );
        }
        //Mr John Doe of 23 London Rd, Redhill
        $extra="";
        $title="";
        $middleName="";
        if ($responseTypeID==1){
            $extra=' of ' . $user[0]->address;
            $title=$user[0]->title;
        }else if ($responseTypeID==2){
            $middleName=$user[0]->middleName;
        }
        $fullusername = $title . ' ' . $user[0]->firstName . ' ' . $middleName . '  ' .   $user[0]->lastName . $extra;
            
        $companyID=$user[0]->companyID;
        //Real company details
        $selectCompany=DB::select('select companyName, companyID from companyprofiletable where companyID=? ',  [$user[0]->companyID]);
        if ($selectCompany){
            View::share('mobile_companyName', $selectCompany);
        }
                
        //Get the questions details
        $quesType = DB::select('select * from questable');

        //Work on the date_of_interest
        $date = Carbon::createFromFormat('Y-m-d',  $resp[0]->date_of_interest);
        $date_of_interest = $date->format('F') . ' ' . $date->format('Y');
   
        //Get the service users question form
        $quesForm=DB::select('select * from ' . $quesFormTable . ' where companyID=? and responseTypeID =? ', [$companyID, $responseTypeID]);
          
        return view($quesFormPage, ['username' => $fullusername,'quesType' =>$quesType,'unique_value'=> $resp[0]->unique_value,
           'quesForm' => $quesForm ,  'quesCount'=>count($quesForm), 'userID' => $resp[0]->userID, 
           'campaign' => $campaign, 'responseTypeID' => $responseTypeID , 'date_of_interest' =>$date_of_interest ]);
    }

    public function save_userFeedback(Request $request){
        
        $userID=$request->userID;
        $responses=$request->responses;
        $quesName=$request->quesName;
        $quesTypeID=$request->quesTypeID;
        $CQCid=$request->CQCid;
        $quesOptions=$request->quesOptions;
        $responseTypeID=$request->responseTypeID;       
        $unique_value=$request->unique_value;

        $where=
        [
           ['userID', $userID],
           ['responseTypeID', $responseTypeID],
           ['unique_value', $unique_value]
        ];
        
        $q=DB::table('responsetable')
            ->where($where)
            ->whereNull('date_received')
            ->whereNotNull('date_posted')
            ->update([
                   'date_received' => Carbon::now(),
                   'responses' => $responses,
                   'quesName' => $quesName,
                   'quesTypeID' => $quesTypeID,
                   'CQCid'=>$CQCid,
                   'quesOptions' => $quesOptions,
                   ]);

       return response()->json($request); 
    }   
    
    public function successSaved(Request $request){
        $mobile_companyName=DB::select('select companyName from companyprofiletable where companyID=? ', [$request->companyID]);
       return view('mobile.pages.successSaved',
         ['userType' => 'Customer', 'campaign' => '',
         'mobile_companyName' => $mobile_companyName,
         'username' => "Information Submitted", 'date_of_interest'=>''] );
    }   

}
