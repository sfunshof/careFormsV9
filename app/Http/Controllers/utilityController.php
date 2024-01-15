<?php

namespace App\Http\Controllers;

//use GuzzleHttp\Exception\GuzzleException;
//use GuzzleHttp\Client;

use ClickSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\employee_feedbackMail;

class utilityController extends Controller
{
    //
    public function send_smsMsg($from,$to,$msgX){
        // Configure HTTP basic authorization: BasicAuth
        $config = ClickSend\Configuration::getDefaultConfiguration()
        ->setUsername(config('care.click_send_username'))
        ->setPassword(config('care.click_send_password'));

        $apiInstance = new ClickSend\Api\SMSApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new \GuzzleHttp\Client(),
            $config
        );
        
        $msg = new \ClickSend\Model\SmsMessage();
        $msg->setFrom($from);
        $msg->setBody($msgX);
        $msg->setTo($to);
        $msg->setSource("sdk");
        $sms_messages = new \ClickSend\Model\SmsMessageCollection();
        $sms_messages->setMessages([$msg]);

        try {
               //$result = $apiInstance->smsSendPost($sms_messages);
            
            //print_r($result);
            return 1;
        } catch (Exception $e) {
            echo 'Exception when calling AccountApi->accountGet: ', $e->getMessage(), PHP_EOL;
            return -1;
        }
    }

    public function send_emailMsg($userID,$subject, $msgX,$emp_or_su){
        $company_setting=$this->company_settings[0];
        $companyID=$company_setting->companyID;
        $companyName=$company_setting->companyName;
        $preText=$company_setting->smsPreTextSu;
        $table='serviceuserdetailstable';
        $titlemsg="Service User Feedback";
        if ($emp_or_su==2){
            $preText=$company_setting->smsPreTextEmp;
            $table='employeedetailstable';
            $titlemsg="Employee Feedback";
        }

        //get the email address
        $user = DB::table($table)
        ->select('email')
        ->where(['userID'=>$userID,  'companyID' => $companyID])
        ->get();
        $email='';
        if (count($user)){
           $email=$user[0]->email; 
        }   

        $details=[
                'msg'=> $msgX,
                'name'=> $companyName,
                'subject' => $companyName . ' ' . 'Monthly survey',
                'title' => $titlemsg
        ];    
        Mail::to($email)->send(new employee_feedbackMail($details)); 
        return 1;
       
    }
    
    private function flip($input) {
        return ($input == 0 || $input == 1) ? 1 - $input : "Invalid input";
    }

    public function user_sendSMS(Request $req){
        //var_dump("hello");
        $company_setting=$this->company_settings[0];
        $companyID=$company_setting->companyID;

        $from =$company_setting->smsName;
        $to=$req->tel;
        $userID=$req->userID;
        $statusID=$req->statusID;
        $responseTypeID=$req->responseTypeID;
        $date_of_interest=$req->date_of_interest;
        $isSMS=$req->sms;
        $sentCount=$req->sentCount;
        $sentEmailCount=$req->sentEmailCount;
          
        $isEmail=$this->flip($isSMS);
        $sentDeliveryCount=$sentCount;
        $sentDeliveryCountStatus='sentCount';
        if ($isSMS==0){
            $sentDeliveryCount=$sentEmailCount;
            $sentDeliveryCountStatus='sentEmailCount';
        }
        $newCount=$sentDeliveryCount+1;
        if ($newCount >2) $newCount=2;

        //if it is the first time, statusID==1 we need to generate a unique ID
        $unique_value=substr(md5(uniqid(rand(), true)),0,7);
        
        if ($statusID==2){ //2nd time please check if thius is 1st 2nd time 
           
            $date = Carbon::parse($date_of_interest);
            $month = $date->format('m');
            $year = $date->year;
           
            $resp = DB::table("responsetable")
            ->select('unique_value', $sentDeliveryCountStatus)
            ->whereNull('date_received')  
            ->whereMonth('date_of_interest', $month)
            ->whereYear('date_of_interest', $year)
            
            ->where(['userID'=>$userID, 'responseTypeID' =>$responseTypeID, 'companyID' => $companyID])
            ->get();
            if (count($resp)){
               $unique_value=$resp[0]->unique_value; 
               //$sentCount=$resp[0]->sentCount;
               //$sentEmailCount=$resp[0]->sentEmailCount;
               $sentDeliveryCount =$resp[0]->$sentDeliveryCountStatus;
            }
            //return response()->json([$unique_value ]); 
        }
        $feedbackMsg="";
        $smsPreText="";
        $lineBreak=" %0a " ;
        $URL= url( '/' .  $unique_value); 
        if ($isSMS==0){
            $lineBreak=" <br> " ;
            $URL= "<a href= " . $URL . "> " .  $URL . "</a>";
        }
        if  ($responseTypeID==1) {
            $smsPreText=$company_setting->smsPreTextSu;
            $feedbackMsg="Service User Feedback";
        }
        
        if  ($responseTypeID==2){
            $smsPreText=$company_setting->smsPreTextEmp;
            $feedbackMsg="Employee Feedback";
        }
        $msg= $smsPreText  .$lineBreak . $URL;
              
        //If this is the first time or a first resend  send it already
        //done at the clients
        $ok_to_send=1;

        //if sending by sms and sentCount=2 do not send 
        

        if (($sentDeliveryCount==2) && ($statusID==2)){
            $ok_to_send=0;
        }
        
       
        if ($ok_to_send==1){
            $result=-1;
            if ($isSMS==1){
                $result=$this->send_smsMsg($from,$to,$msg);
            }else if ($isSMS==0){
                $result=$this->send_emailMsg($userID, $feedbackMsg, $msg, $responseTypeID);
            }    

            //On success delivery the table should be updated
            //return  response()->json([$result, $statusID]); 
            if (($result==1)&& ($statusID==1)) { //1st time
                $q=DB::table('responsetable')
                    ->where([
                        ['userID', $userID],
                        ['responseTypeID', $responseTypeID],
                        ['companyID', $companyID]
                    ])
                ->whereNull('date_posted')
                ->whereNull('date_received')        
                ->update([
                    'date_posted' => Carbon::now(),
                    $sentDeliveryCountStatus => 1,
                    'date_of_interest' =>$date_of_interest,
                    'unique_value' => $unique_value,
                    'sendByEmail' => $isEmail
                ]);
            }elseif (($result==1)&& ($statusID==2)) { //snd time res-send
                //return  response()->json([$userID, $responseTypeID, $companyID]); 
                $q=DB::table('responsetable')
                ->where([
                        ['userID', $userID],
                        ['responseTypeID', $responseTypeID],
                        ['companyID', $companyID]
                    ])
                ->whereNull('date_received')    
                ->whereIn( $sentDeliveryCountStatus, [0,1,2])  
                ->update([
                    'date_posted' => Carbon::now(),
                     $sentDeliveryCountStatus => $newCount,
                    'sendByEmail' => $isEmail
                ]);
            }
            return response()->json($result); 
        }
        return response()->json(10); 
    }
    public function serviceUser_viewResponse(Request $req){
        print_r($req);
        return 0;
    }

    
}
