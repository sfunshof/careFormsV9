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
            return view('mobileprospect.pages.homepage')->with($arrayDetails);
        }else{
            return redirect()->route('compliancelogin');
        }
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
            //if an update simply rupdate and return
            if ($userID >=0){
                 //updates
                DB::table('responsetable_prospect')
                    ->where('serviceUserID', $userID)
                    ->update([
                    'responses' => json_encode($prospectData),
                ]);
                return response()->json(['success'=>'Updated assessment', 'status' => 1]);
            }
            
            // Retrieve the service userID
            $serviceUserID = DB::table('serviceuserdetailstable')
                ->where('randomNo', $randomDigits)
                ->value('userID');

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
            DB::table('responsetable_prospect')->insert([
                'companyID' => $companyID,
                'serviceUserID' => $serviceUserID,
                'supervisor' => $userName,
                'responses'=> json_encode($prospectData),
                'quesNames' =>$quesNames,
                'quesTypeID' => $quesTypeID,
                'quesOptions' => $quesOptions,
                'date_issue' => now(),
            ]);
            
            return response()->json(['success'=>'Added new  assessment', 'status' => 1]);
        }else{
            return redirect()->route('compliancelogin');
        }   
    }
 

}
