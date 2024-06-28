<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;//Use at top of the pag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 
use Barryvdh\DomPDF\Facade\PDF;


class serviceUserController extends Controller
{
    
    public function getReligions(){
       // Fetch religion names from 'religion' table
        $religions = DB::table('religiontable')
            ->select('religionName')
            ->get();

        // Extract religion names into an array
        $religionNames = [];
        foreach ($religions as $religion) {
            $religionNames[] = $religion->religionName;
        }
        return $religionNames;
    }    
    //
    public function addnew_serviceUser(){
        $religion=$this->getReligions();
        return view('backoffice.pages.addnew_serviceUser', 
        ['user'=>'', 'isProspect' =>0, 'religionArray' =>$religion, 
        'serviceUser'=> 'Service User',  'count' => -1, 'isView' => 0, 'isPrint' => 0, 'userID' =>-1]);
    }   

    //isView=AddNew, Update, View, Print
    public function addnew_prospect(){
        $religion=$this->getReligions();
        $quesArray=$this->get_prospectQues();
        return view('backoffice.pages.addnew_serviceUser', 
        [
            'user'=>'', 'isProspect' =>1,'religionArray' =>$religion, 
            'serviceUser'=> 'Assessment', 'spotCheckQues' => $quesArray,
            'count' => count($quesArray), 'showAll' => 1, 'isView' => 0, 'isPrint' => 0, 'userID' => -1
          ]);
    }   
    
    private function generateRandomNumberBasedOnTime(){
        $currentTime = Carbon::now();
        // Extract date, hour, minute, and second components
        $year = $currentTime->year;
        $month = $currentTime->month;
        $day = $currentTime->day;
        $hour = $currentTime->hour;
        $minute = $currentTime->minute;
        $second = $currentTime->second;
        // Combine time components into a single seed value
        $seed = $year * 10000000000 + $month * 100000000 + $day * 1000000 + $hour * 10000 + $minute * 100 + $second;
        // Seed the random number generator
        mt_srand($seed);
        // Generate a random number
        $random = mt_rand();
        return $random;
    }



    public function save_serviceUser(Request $req){
        $userID=$req->userID;
        $companyID = Session::get('companyID');
        //check if you updated without filling the prospect ques
        if ($userID >=0){
            //Do nothing
        }else{
            $userID=-1;
        }

        if ($userID==-1){
            $prospectRandomNo=$req->prospectRandomNo;
            if ($prospectRandomNo){
                $userID_temp = DB::table('serviceuserdetailstable')
                    ->where('randomNo', '=', $prospectRandomNo)
                    ->value('userID');
                if ($userID_temp>=0){
                    $userID=$userID_temp;
                }
            }        
        } 
        
        $rules= ([
            'firstName' => 'required|max:20',
            'lastName' => 'required|max:20', 
            'postCode' => 'required|min:6|max:10',
            'mobile' =>  ['required', 'regex:/^\+44\d{7,11}$/'],
            'email' => 'required|email',
        ]);
        
        // Add uniqueness rule for email if it's a new user
        if (!$userID) {
            $rules['email'] .= '|unique:serviceuserdetailstable,email';
        } else {
            // Validation rules for updating an existing user
            $rules['email'] .= '|unique:serviceuserdetailstable,email,' . $userID . ',userID';
        }

        $rules_prospect= ([
            'NiN' => 'required|max:20|min:4',
            'NhsN' => 'required|max:20|min:4', 
            'address' => 'required|min:6|max:30',
            'DOB'=>'required'
        ]);
        $msg=[
            'NiN.required'=>'NI Number is required',
            'NiN.max'=>'NI Number has max of 20',
            'NiN.min'=>'NI Number has min of 4',
            'NhsN.required'=>'Nhs Number is required',
            'NhsN.max'=>'Nhs Number has max of 20',
            'NhsN.min'=>'Nhs Number has min of 4',
            'DOB' => 'Date of birth is required'
        ];
        
         // Combine the rules and messages
        $randomNo='';
        if ($req->isProspect==1){
            $rules = array_merge($rules, $rules_prospect);
            $randomNo= $this->generateRandomNumberBasedOnTime();
            //$msg = array_merge($msg, $COSmsg);
        }
        $validator = Validator::make($req->all(), $rules, $msg);
        $prospectArray=['Nin'=>$req->NiN,
                       'Nhs' => $req->NhsN,
                       'DOB' => $req->DOB,
                       'address' => $req->address,
                       'religion' => $req->religion,
                       'gender' =>$req->gender];
          

        if ($validator->passes()) {
            $fieldSet=[
                'title'=>$req->title,
                'firstName'=>$req->firstName,
                'lastName' =>$req->lastName,
                'address' =>$req->postCode,
                'tel'=>$req->mobile,
                'proxy'=>$req->proxy,
                'companyID'=>$companyID,        //$req->companyID,
                'email'=>$req->email,
            ];
            
            //if it is serviceUser, do not update protect data
            if ($req->isProspect==1){
                $fieldSet['prospectJSON']=json_encode($prospectArray);
                $fieldSet['randomNo']=$randomNo; 
            }
            
            if ($userID==-1){
                $fieldSet['isProspect']=$req->isProspect;
                $insertStatus=DB::table('serviceuserdetailstable')->insert($fieldSet);
                if ($insertStatus==1){
                    return response()->json(['success'=>'Added new records.',
                                         'status' => 1,
                                         'randomNo' => $randomNo, 'prospect' => $req->isProspect]);
                }else{
                    return response()->json(['Error'=>'Problems with the database',
                                         'status' => -1]);
                }
            }else if ($userID >0){ //for the update we do not touch the isProspect it can be 0 or 1
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
        //DB::update('update serviceuserdetailstable set isdisable = ? where userID = ?',[1,$userID]);
        DB::table('serviceuserdetailstable')
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

    public function enable_serviceUser(Request $req){
        $userID= $req->userID;
        //DB::update('update serviceuserdetailstable set isdisable = ? where userID = ?',[0,$userID]);
        DB::table('serviceuserdetailstable')
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
    
    public function convert_prospect(Request $req){
        $userID= $req->userID;
        //DB::update('update serviceuserdetailstable set isdisable = ? where userID = ?',[1,$userID]);
        DB::table('serviceuserdetailstable')
            ->where('userID', $userID)
            ->update([
                'isProspect' => 0,
            ]);
        return response()->json([
            'success'=>'Updated records.',
            'status' => 1]
        );
    }

    private function if_any_disabledUsers($isProspect){
        $recordExists = DB::table('serviceuserdetailstable')
        ->where('isProspect', $isProspect)
        ->exists();
        if ($recordExists){
            return 1;
        }else{
            return 0;
        }    
    }

    public function browse_serviceUsers(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('isDisable', 0)
        ->where('companyID', $companyID)
        ->where('isProspect', 0)
        ->get();
        $exist= $this->if_any_disabledUsers(0);
       return view('backoffice.pages.browse_serviceUsers', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1, 'exist' => $exist]);
    }   
    
    public function browse_all_serviceUsers(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('companyID', $companyID)
        ->where('isProspect', 0)
        ->get();
       return view('backoffice.pages.browse_serviceUsers', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag'=> 0, 'exist' => 0]);
    } 
    
    public function browse_prospects(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(title, ' ', firstName,' ',lastName) as fullName"), DB::raw('DATE(createdDate) as createdDate'))
        ->where('isDisable', 0)
        ->where('companyID', $companyID)
        ->where(function ($query) {
            $query->where('isProspect', 1)
                  ->orWhereNotNull('randomNo');
        })
        ->get();
        
        $exist= $this->if_any_disabledUsers(1);
       return view('backoffice.pages.browse_prospects', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag' => 1, 'exist'=>$exist ]);
    }   

    public function browse_all_prospects(Request $req){
        $pageNo=$req->pageNo;
        $companyID=$this->company_settings[0]->companyID;
        $user = DB::table("serviceuserdetailstable")
        ->select("*", DB::raw("CONCAT(firstName,' ',lastName) as fullName"))
        ->where('companyID', $companyID)
        ->where(function ($query) {
            $query->where('isProspect', 1)
                  ->orWhereNotNull('randomNo');
        })
        ->get();
       return view('backoffice.pages.browse_prospects', ['serviceUsers'=> $user, 'pageNo' => $pageNo, 'isDisabledFlag'=> 0, 'exist' => 0]);
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
   
    private function get_prospect_Ques($userID){
        $results = DB::table('responsetable_prospect')
        ->select('quesNames', 'quesTypeID', 'quesOptions', 'responses', 'supervisor', 'date_issue')
        ->where('serviceUserID', $userID)
        ->get();
        $quesResultsArray = [];
        $responsesArray=[];
        $accessByArray= [];
        foreach ($results as $row) {
            $quesNames = json_decode($row->quesNames);
            $quesTypeIDs = json_decode($row->quesTypeID);
            $quesAttribs = json_decode($row->quesOptions);
            $responsesArray = json_decode($row->responses, true);
            for ($i = 0; $i < count($quesNames); $i++) {
                $quesResultsArray[] = [
                    'quesName' => $quesNames[$i],
                    'quesTypeID' => $quesTypeIDs[$i],
                    'quesAttrib' =>  $quesAttribs[$i]
                ];
            }
        }
        $array['quesResultArray']=$quesResultsArray;
        $array['responseArray']=$responsesArray;
        $array['accessByArray']=$results;
        return  $array;
    }  

    
    //updates service user from modal
    public function get_serviceUserDetails(Request $req){
        $userID=$req->userID;
        $userDetails= $this->get_serviceUser_details($userID);
        $view = (string)View::make('backoffice.inc.serviceUserFields',
        ['user'=>$userDetails[0], 'isProspect' => 0,'religionArray' => [], 'count'=> -1,
          'isPrint' => 0, 'userID' =>$userID
        ]); 
        return response()->json($view); 
    }
        

    //isView=AddNew, Update, View, Print
    public function get_prospectDetails(Request $req){
        $userID=$req->userID;
        $isPrint=$req->isPrint;
        $userDetails= $this->get_serviceUser_details($userID);
        $religion=$this->getReligions();
        $array=$this->get_prospect_ques($userID);
        
        $quesResultArray=$array['quesResultArray'];
        $responsesArray =$array['responseArray'];
        $accessByArray=$array['accessByArray'];
        //return $quesResultArray;
        //$quesResultArray=$this->get_prospectQues();
        
        $view = (string)View::make('backoffice.inc.serviceUserFields',
        ['user'=>$userDetails[0], 'isProspect' => 1, 'religionArray' => $religion, 
            'spotCheckQues' => $quesResultArray,
            'count' => count($quesResultArray), 'showAll' => 1, 'isView' =>1, 
            'responsesArray' => $responsesArray, 'isPrint' => $isPrint, 'print_' =>0,
            'accessByArray'=>$accessByArray, 'userID' => $userID
        ]); 
        return response()->json($view); 
    }
    
    public function pdf_prospectDetails(Request $req){
               
        $userID=$req->userID;
        $isPrint=1;
        $userDetails= $this->get_serviceUser_details($userID);
        $religion=$this->getReligions();
        $array=$this->get_prospect_ques($userID);
        
        $quesResultArray=$array['quesResultArray'];
        $responsesArray =$array['responseArray'];
        $accessByArray=$array['accessByArray'];

        $proxyArray=['Service User', '3rd Party'];
        $gender=['Male', 'Female'];

        $jsonData=$userDetails[0]->prospectJSON;
        // Decode the JSON field to an associative array
        $details = json_decode($jsonData, true);
        $NiN = $details['Nin'];
        $NhsN = $details['Nhs'];
        $DOB = $details['DOB'];
        $address=$details['address']; //** watch it

        $userGender =   $details['gender'];
        $userReligion = $details['religion'];

        $arrayTable=[
            0=>'Personal Information',
            1=> 'Assessment Information',
           /* 2=> 'Assessment Questionnaire Data' */
        ];
        $data[0]=[
              [
                'Name' => $userDetails[0]->title,
                'Col' => 2,
                'Row' => 1,
                'title' => 'Title'
              ],
              [
                'Name' => $userDetails[0]->firstName,
                'Col' => 4,
                'Row' => 1,
                'title' => 'First Name'
              ],
              [
                'Name' => $userDetails[0]->lastName,
                'Col' => 4,
                'Row' => 1,
                'title' => 'Last Name'
              ],
               
              [
                'Name' => $userDetails[0]->address,
                'Col' => 2,
                'Row' => 1,
                'title' => 'Post Code'
              ],
              [
                'Name' => $userDetails[0]->tel,
                'Col' => 4,
                'Row' => 2,
                'title' => 'Mobile'
              ],
              [
                'Name' => $userDetails[0]->email,
                'Col' => 5,
                'Row' => 2,
                'title' => 'Email'
              ],
            
              [
                'Name' => $proxyArray[$userDetails[0]->proxy],
                'Col' => 3,
                'Row' => 2,
                'title' => 'Whose Mobile'
              ],
 
        ];
        
        $data[1]=[
              [
                'Name' => $NiN,
                'Col' => 4,
                'Row' => 1,
                'title' => 'N.I. Number'
              ],
              
              [
                'Name' => $NhsN,
                'Col' => 4,
                'Row' => 1,
                'title' =>  'NHS Number'
              ],
              [
                'Name' => $DOB,
                'Col' => 4,
                'Row' => 1,
                'title' =>  'Date of Birth'
                
              ],
    
              [
                'Name' => $religion[$userReligion],
                'Col' => 4,
                'Row' => 2,
                'title' =>  'Religion'
              ],
              
              [
                'Name' => $gender[$userGender],
                'Col' => 3,
                'Row' => 2,
                'title' =>  'Gender'
              ],
              [
                'Name' => $address,
                'Col' => 5,
                'Row' => 2,
                'title' =>  'First Line of Address'
              ],
        ];
         $pdf = PDF::loadView('backoffice.pages.pdf_prospects',
         ['arrayTable'=>$arrayTable, 'data'=>$data,
           'spotCheckQues' => $quesResultArray,
           'count' => count($quesResultArray),
           'responsesArray' => $responsesArray,
           'accessByArray'=>$accessByArray
         ]
        ); 
        return $pdf->download('assessment.pdf');
    }    
   
    
    private function get_prospectQues(){
        $companyID = Session::get('companyID');
         //**** Copied from spotcheck : spotcheckQues  ***/
        // Spot Check questionnaire
        $results = DB::table('buildformtable_prospect')
        ->select('quesName', 'quesTypeID', 'quesAttrib')
        ->where('companyID', $companyID)
        ->get();

        // Initialize an array to store the results
        $quesResultsArray = [];
    
        // Populate the array
        foreach ($results as $row) {
            $quesResultsArray[] = [
                'quesName' => $row->quesName,
                'quesTypeID' => $row->quesTypeID,
                'quesAttrib' => $row->quesAttrib,
            ];
        }
        return $quesResultsArray;
    }    



    
    public function show_compliments_serviceUser(){
        return view('backoffice.pages.show_compliments_serviceUser');
    }
    
    public function show_complaints_serviceUser(){
        return view('backoffice.pages.show_complaints_serviceUser');
    }

}
