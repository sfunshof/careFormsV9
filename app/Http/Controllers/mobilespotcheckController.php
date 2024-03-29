<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\backofficeController;

class mobilespotcheckController extends Controller
{
    //
    public function showLoginForm(){
        //$companyID=$this->company_settings[0]->companyID;
        $companyDetails=[];
        //Session::flush();
        Session::forget('companyID');
        return view('mobilespotcheck.pages.loginpage', [ $companyDetails] );
    }    
  
    public function showHomePage(){
        if (session()->has('companyID')){
            $arrayDetails=[
                'records' =>[]
            ];
                        
            return view('mobilespotcheck.pages.homepage', [ $arrayDetails] );
        }else{
            return redirect()->route('spotchecklogin');
        }
    }    
  

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('userstable')
        ->where('email', $request->email)
        ->first();
        if ($user && Hash::check($request->password, $user->password)) {
            // Custom logic (e.g., update last login timestamp)
            $userName=$user->email;
            $companyID=$user->companyID;
            $userID=$user->id;
           
            $companyName = DB::table('companyprofiletable')
            ->where('companyID', $companyID)
            ->value('companyName');
            $viewArray=['companyID' => $companyID, 'companyName' => $companyName, 'userID' =>$userID, 'userName' => $userName]; 
      
            //go into the database and bring up all the users
            $carers = DB::table('employeedetailstable')
                ->where([
                    ['companyID', $companyID],
                    ['jobFunction', 0],
                    ['isDisable', 0],
                ])
                ->select('firstName', 'lastName', 'userID')
                ->get();
            // Initialize an empty array to store item_names and item_ids
            $carersArray = [];

            // Populate the array with item_names and item_ids
            foreach ($carers as $carer) {
                $carersArray[] = [
                    'firstName' =>$carer->firstName,
                    'lastName' =>$carer->lastName,
                    'userID' =>$carer->userID,
                ];
            }
            
            //service users
            $serviceUsers = DB::table('serviceuserdetailstable')
            ->where([
                ['companyID', $companyID],
                ['isDisable', 0],
            ])
               ->select('useriD as userID', DB::raw("CONCAT(title, ' ', firstName,'  ',lastName, ' of ', address) AS name"))
               ->get();
              // Populate the array with item_names and item_ids
              $serviceUsersArray=[];
              foreach ($serviceUsers as $serviceUser) {
                $serviceUsersArray[] = [
                    'name' =>$serviceUser->name,
                    'userID' =>$serviceUser->userID,
                ];
            }    
            
            // Spot Check questionnaire
            $results = DB::table('buildformtable_spotcheck')
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


            //Go into the reponse_spotcheck table and list all the last records
            //userID->Date  1=> "2023-10-01 with Alex , 2 => "2024-01-02  with Mary , 3=> "2022-01-02 with Jhn          
            $lastRecsArray=[];
            
            $viewArray['count']= count($quesResultsArray);
            $viewArray['spotCheckQues']= $quesResultsArray; 
       
            $viewArray['carers']=$carersArray;     
            $viewArray['lastRecs']=$lastRecsArray;
            $viewArray['serviceUsers']=$serviceUsersArray;
            
            // Save the companyID into the session
            Session::put('companyID', $companyID);
            $spotCheckData = (new backofficeController)->get_mobile_spotcheck_data();
            $viewArray['records']=$spotCheckData['records'];
            $viewArray['not_yet_spotCheckedIDs']=$spotCheckData['not_yet_spotCheckedIDs'];
            $viewArray['carerNames']=$spotCheckData['carerNames'];
            $viewArray['employee_data']=$spotCheckData['employee_data'];
            
            Session::put($viewArray);
            return view('mobilespotcheck.pages.homepage',$viewArray);
        }
        $errors = new MessageBag(['validates' => ['Email and/or password invalid.']]);
        return redirect()->back()->withErrors($errors)->withInput($request->except('password'));
    }
    
    public function saveSpotCheckData(Request $request){
        // Retrieve data from the request
        
        $carerID = $request->input('carerID');
        $serviceUserID = $request->input('serviceUserID');
        $spotCheckData=$request->input('spotCheckData');
        $spotCheckReview=$request->input('spotCheckReview');
        $companyID = session()->get('companyID');
        $userName = session()->get('userName');
        $spotCheckEverything=session()->get('spotCheckQues');
        $count=session()->get('count');
        //This technique is to separate 
        $quesNames=[];
        $quesTypeID=[];
        $quesOptions=[];
        for ($i=0;$i< $count; $i++){
            $quesNames[$i]= $spotCheckEverything[$i]['quesName'];
            $quesTypeID[$i] = $spotCheckEverything[$i]['quesTypeID'];
            $quesOptions[$i] = $spotCheckEverything[$i]['quesAttrib'];
        }
        //end of the separation

        $quesNames=json_encode($quesNames);
        $quesTypeID=json_encode($quesTypeID);
        $quesOptions = json_encode($quesOptions);
                // Save data to the MySQL table
        DB::table('responsetable_spotcheck')->insert([
            'companyID' => $companyID,
            'carerID' => $carerID,
            'serviceUserID' => $serviceUserID,
            'supervisor' => $userName,
            'responses'=> json_encode($spotCheckData),
            'quesNames' =>$quesNames,
            'quesTypeID' => $quesTypeID,
            'quesOptions' => $quesOptions,
            'date_issue' => now(),
            'star' => $spotCheckReview
            
             // Add other columns as needed
        ]);
        
        return response()->json(['message' => 'Data Saved']);
    
    }
 
    
}
