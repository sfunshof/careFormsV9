<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class formsController extends Controller
{
    //
    public function build_formFunction($resTypeID){
        $companyID=$this->company_settings[0]->companyID;
        $cqc = DB::table("cqctable")
        ->select("*")
        ->get();
        $ques = DB::table("questable")
        ->select("*")
        ->get();
        $forms=DB::table("buildformtable")
        ->select("*")
        ->where(['companyID'=>$companyID, 'responseTypeID' => $resTypeID])
        ->get(); 
        $options=DB::table("optionstable")
        ->select("*")
        ->get(); 
        $result['cqc']=$cqc;
        $result['ques']=$ques;
        $result['forms']=$forms;
        $result['options']=$options;
        return $result;

    }
    
    public function update_form(Request $req){
        $insertData=$req->data;
        $resTypeID=$req->responseTypeID;
        //keep the old form data but delete afterwards by setting everything to -1
        //This is excellent for rollback
        $companyID=$this->company_settings[0]->companyID;
        DB::table('buildformtable')
        ->where(['companyID' => $companyID, 'responseTypeID' => $resTypeID])
        ->update(['companyID' => $companyID *-1, 'responseTypeID'=> $resTypeID *-1, 
        'updateDate'=>now()]);
              
        $status=-1;
        foreach ($insertData as $item) {
            $status=DB::table('buildformtable')->insert($item);
        }
        return response()->json([
            'status' => 1]
        );
    }


    //From here downwards begin to build the forms for each category
    public function build_serviceUserFeedback(){
        $result= $this->build_formFunction(1);
        $cqc=$result['cqc'];
        $ques=$result['ques'];
        $forms=$result['forms'];
        $options=$result['options'];
        return view('backoffice.pages.build_form', 
        ['cqcArray' => $cqc, 'quesArray'=>$ques, 'forms' =>$forms, 
         'title' =>' Service User Feedback forms', 'respTypeID' => 1, 'options' =>$options ]);
    }   
    
    public function build_employeeFeedback(){
        $result= $this->build_formFunction(2);
        $cqc=$result['cqc'];
        $ques=$result['ques'];
        $forms=$result['forms'];
        $options=$result['options'];
        return view('backoffice.pages.build_form', 
        ['cqcArray' => $cqc, 'quesArray'=>$ques, 'forms' =>$forms, 
         'title' =>' Employee Feedback forms', 'respTypeID' => 2, 'options' =>$options]);
    }   
    
    //** This is for service user and employee survey */
    static function survey_status($date,$userTable,$resTypeID,$companyID,$sendByEmail){
        // Insert everytime when date_posted is not null
        $insert=  "insert into responsetable (userID, responseTypeID, companyID,sendByEmail) 
        select userID," . $resTypeID .  "," .  $companyID .  "," . $sendByEmail  . " from " . $userTable . " WHERE
        userID not in 
        (select userID from responsetable  where date_posted  is null
            and responseTypeID =? and userID in (select userID from " . $userTable . " )
         )";
        $insertStatus=DB::insert($insert,  [$resTypeID]);    
        //** End of insert  */
        //Do not allow for sending of sms to Deactivated users
        $disabled=" Delete from responsetable where date_posted is null  
                  and responseTypeID=? and companyID= ?   and userID in 
                  (select userID from " . $userTable . " where isDisable=1)"; 
        DB::delete($disabled, [$resTypeID, $companyID]);    


        //** 1st get the ones with date posted */
        //** 2nd get the blank ones that do not have posted date as blank */
        $selectFeedback=" SELECT *  FROM responsetable
            WHERE  YEAR(date_of_interest) = YEAR( '" .  $date . "')
            AND  MONTH(date_of_interest) = MONTH( '" . $date . "')
            AND  responseTypeID=?  and companyID =?
        UNION
            SELECT *  FROM responsetable
            WHERE responseTypeID=?  and companyID=?   AND  date_posted is null and userID not in 
            (SELECT userID  FROM responsetable
                WHERE  YEAR(date_of_interest) = YEAR('" . $date . "')
                AND MONTH(date_of_interest) = MONTH('" . $date . "')  
                AND responseTypeID=? and companyID=? 
            )";
        $responseStatus = DB::select($selectFeedback, [$resTypeID, $companyID, $resTypeID,  $companyID,   $resTypeID, $companyID]);
        $selectDetails="select * from " . $userTable;
        $usersDetails=DB::select ($selectDetails);
        $result=array();
        $result['userDetails']=$usersDetails;
        $result['responseStatus']=$responseStatus;
        return $result;
    }
  
    static function set_surveyDate($req){
        $pageNo=$req->pageNo;
        $month=$req->month;
        $year=$req->year;
        $date="";
        $dateFlag=0;      
        if (!$month){ //Standard outputs
            $date=  new Carbon('first day of last month'); //last month default
            $month = $date->format('m');
            $year=$date->format('Y');
        }else{ //selected dates
            $date=$year . "-" . $month . "-01";
            $selectedtDate = Carbon::create($date);
            $first_day_last_month=  new Carbon('first day of last month'); //last month default 

            //$first_day_of_curent_month = Carbon::now()->startOfMonth()->toDateString();
            //$todayDate= Carbon::create($first_day_of_curent_month);
            if ($selectedtDate->gt($first_day_last_month)){
                $dateFlag=1;  
                $date=  new Carbon('first day of last month'); //last month default
                $month = $date->format('m');
                $year=$date->format('Y');
            }
        }
        $result['pageNo']=$pageNo;
        $result['dateFlag']=$dateFlag;
        $result['month']=$month;
        $result['year']=$year;
        $result['date']=$date;
        return $result;
        
    }
    
        
    private function get_responseType($responseTypeID){
        $resp = DB::table("responsetypetable")
        ->select('*')
        ->where(['responseTypeID'=>$responseTypeID ])
        ->get();
        return $resp;
    }

    private function get_feedbackResponses($userID, $unique_value, $responseTypeID){
        $resp = DB::table("responsetable")
        ->select('*')
        ->where(['userID'=>$userID, 'unique_value'=> $unique_value, 'responseTypeID'=>$responseTypeID ])
        ->get();
        return $resp;   
    }
    public function view_feedback(Request $req){
       
        $userID= $req->userID;
        $unique_value=$req->unique_value;
        $responseTypeID=$req->responseTypeID;
        
        $respX=$this->get_feedbackResponses($userID, $unique_value, $responseTypeID);
        $date_posted=$respX[0]->date_posted;
        $date_received=$respX[0]->date_received;
        $response=[];
        $quesName=[];
        $quesTypeID=[];
        $quesOptions=[];
        $fullName='';
        $companyName=$this->company_settings[0]->companyName;
        $responseType='';
        if ($respX){
            $response=$respX[0]->responses;
            $quesName=$respX[0]->quesName;
            $quesTypeID=$respX[0]->quesTypeID;
            $quesOptions=$respX[0]->quesOptions;
        }
        
        $date = Carbon::parse($respX[0]->date_of_interest);
        $month = $date->format('F');
        $year= $date->format('Y');

        //Lets get the details
        $fullName="";
        if ($responseTypeID==1){
            $user = DB::table("serviceuserdetailstable")
            ->select("*")
            ->where(['userID'=>$userID])
            ->get();
            $fullName=$user[0]->title . ' ' . $user[0]->firstName . ' ' . $user[0]->lastName; 
        }else if  ($responseTypeID==2){
            $user = DB::table("employeedetailstable")
            ->select("*")
            ->where(['userID'=>$userID])           
            ->get();
            $fullName=$user[0]->firstName . ' ' . $user[0]->middleName . ' ' . $user[0]->lastName; 
        }    
        
        //Response type
        $resp=$this->get_responseType($responseTypeID);
        $respType='';
        if ($resp){
            $respType=$resp[0]->responseType;
        }
        
        $datetime=Carbon::parse($date_posted);
        $date_posted = $datetime->format('Y-m-d');
        $datetime=Carbon::parse($date_received);
        $date_received = $datetime->format('Y-m-d');

                
        $result['fullName']=$fullName ;
        $result['companyName']=$companyName; 
        $result['datePosted']=" Date Sent: " . $date_posted . "  ";
        $result['dateReceived']=" Date Received: " . $date_received . " ";
        $result['response']=$response;
        $result['quesName']=$quesName;
        $result['quesTypeID']=$quesTypeID;
        $result['quesOptions']=$quesOptions;
        $result['month']=$month . " ";
        $result['year']=$year . ' ';
        $result['respType']=' ' . $respType ;


        return response()->json($result); 
    }    

}
 