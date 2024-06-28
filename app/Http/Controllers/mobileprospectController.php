<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class mobileprospectController extends Controller
{
    public function showHomePage(){
        if (session()->has('companyID')){
            $arrayDetails = Session::get('spotcheckData');
            //$arrayDetails['records'] =[];
            $arrayDetails['user']='';      
            
            //arrays
            $titles = DB::table('titletable')->pluck('titleName');
            $religion=DB::table('religiontable')->pluck('religionName');
            $arrayDetails['titleArray'] = $titles->toArray();
            $arrayDetails['religionArray'] = $religion->toArray();     
            
            $quesResultArray=$this->get_prospectQues();
            $arrayDetails['spotCheckQues']=$quesResultArray;
            $arrayDetails['count']=count($quesResultArray);
            $arrayDetails['uncompleted_prospects']= $this->get_uncompletedProspects();
            return view('mobileprospect.pages.homepage')->with($arrayDetails);
        }else{
            return redirect()->route('compliancelogin');
        }
    }   
    
    private function get_uncompletedProspects(){
        $companyID = session()->get('companyID'); 
       
        $results = DB::table('serviceuserdetailstable')
        ->select('serviceuserdetailstable.*', DB::raw("CONCAT(title, ' ', firstName, ' ', lastName) as fullName"))
        ->where('companyID', $companyID)
        ->where('isDisable', 0)
        ->whereNotNull('prospectJSON')
        ->whereNotIn('userID', function ($query) use ($companyID) {
            $query->select('serviceUserID')
                ->from('responsetable_prospect')
                ->where('companyID', $companyID);
        })
        ->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();
        return $results;
    }
    

    private function get_prospectQues(){
        $companyID = Session::get('companyID');
        if (session()->has('companyID')){
       
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
        }else{
            return redirect()->route('compliancelogin');
        }   
    }    

    public function submit_prospect(Request $request){
        // Retrieve data from the request
        if (session()->has('companyID')){
            $randomDigits = $request->input('randomDigits');
            $prospectData=$request->input('prospectData');
            $userID=$request->userID;
                     
            // Retrieve the service userID
            if ($userID >=0){
                $serviceUserID=$userID;  
            }else {
                $serviceUserID = DB::table('serviceuserdetailstable')
                    ->where('randomNo', $randomDigits)
                    ->value('userID');
            }


            $companyID = session()->get('companyID'); 
            $userName = session()->get('userName');
            $prospectEverything=$this->get_prospectQues();
            $count=count($prospectEverything);
            
            //Log::error($count);
            
            //This technique is to separate 
            $quesNames=[];
            $quesTypeID=[];
            $quesOptions=[];
            $responses=[];
            for ($i=0;$i< $count; $i++){
                $quesNames[$i]= $prospectEverything[$i]['quesName'];
                $quesTypeID[$i] = $prospectEverything[$i]['quesTypeID'];
                $quesOptions[$i] = $prospectEverything[$i]['quesAttrib'];
            }
            //end of the separation

            $quesNames=json_encode($quesNames);
            $quesTypeID=json_encode($quesTypeID);
            $quesOptions = json_encode($quesOptions);
            // Save data to the MySQL table
            //DB::table('responsetable_prospect')->insert();
            
             //We do insert or update here. If upddate from the back office: it is an update
            //if you update from mobile it is inseet. 
            //The backoffice works on the ques itself while the mobile goes from prospect then to 
            //the Ques
            
            $exists = DB::table('responsetable_prospect')->where('serviceUserID', $userID)->exists();

            // Insert or update the record
            DB::table('responsetable_prospect')->updateOrInsert(
                ['serviceUserID' => $serviceUserID], // Condition to check
                [
                    'companyID' => $companyID,
                    'serviceUserID' => $serviceUserID,
                    'supervisor' => $userName,
                    'responses'=> json_encode($prospectData),
                    'quesNames' =>$quesNames,
                    'quesTypeID' => $quesTypeID,
                    'quesOptions' => $quesOptions,
                    'date_issue' => now(),
                ]
            );
            if ($exists) {
                return response()->json(['success'=>' Updated existing assessment', 'status' => 1]);
            } else {
                return response()->json(['success'=>'Added new  assessment', 'status' => 1]);
            }           
         
        }else{
            return redirect()->route('compliancelogin');
        }   
    }
 

}
