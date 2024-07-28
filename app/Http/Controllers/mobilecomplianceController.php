<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\backofficeController;
use Illuminate\Support\Facades\Auth;

class mobilecomplianceController extends Controller
{
    //
    public function showLoginForm(){
        //$companyID=$this->company_settings[0]->companyID;
        $companyDetails=[];
        //Session::flush();
        Session::forget('companyID');
        return view('mobilecompliance.pages.loginpage', [ $companyDetails] );
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
            
            $request->session()->put('loggedIn', true);


            //*** Mileage UserID must be swapped for the one in employeedetails **//
            $is_admin=$user->is_admin;
            Session::put('is_admin', $is_admin);
            $userId=-1;
            $postCode="";
            if($is_admin==0){
                //it is careworker so get the carerworkerID
                $users = DB::table('employeedetailstable')
                    ->select('userID', 'officePostcode')
                    ->where('email', $userName)
                    ->first();
                $userId=$users->userID;
                $postCode=$users->officePostcode;
            }
            Session::put('careWorkerLoginID', $userId);
            Session::put('officePostcode',$postCode);
            //*** End of the Mileage  ***/      
     
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
                ['isProspect', 0],
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
            $viewArray['companyName']=$companyName;
            $viewArray['count']= count($quesResultsArray);
            $viewArray['spotCheckQues']= $quesResultsArray; 
       
            $viewArray['carers']=$carersArray;     
            $viewArray['lastRecs']=$lastRecsArray;
            $viewArray['serviceUsers']=$serviceUsersArray;
            
            // Save the companyID into the session
            Session::put('companyID', $companyID); 
            Session::put('userName',$viewArray['userName']);
            Session::put('spotCheckQues',$viewArray['spotCheckQues']);
            Session::put('count',$viewArray['count']);

            $spotCheckData = (new backofficeController)->get_mobile_spotcheck_data();
            $viewArray['records']=$spotCheckData['records'];
            $viewArray['not_yet_spotCheckedIDs']=$spotCheckData['not_yet_spotCheckedIDs'];
            $viewArray['carerNames']=$spotCheckData['carerNames'];
            $viewArray['employee_data']=$spotCheckData['employee_data'];
            
            Session::put('spotcheckData', $viewArray);
            $response = response()->view('mobilecompliance.pages.menupage');
            return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
            
        }
        $errors = new MessageBag(['validates' => ['Email and/or password invalid.']]);
        return redirect()->back()->withErrors($errors)->withInput($request->except('password'));
    }

    public function showMenuForm(){
        if (session()->has('companyID')){
            return view('mobilecompliance.pages.menupage');
        }else{
            return redirect()->route('compliancelogin');
        }
    }

  
}
