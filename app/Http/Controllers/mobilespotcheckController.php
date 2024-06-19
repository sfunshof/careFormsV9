<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\backofficeController;

class mobilespotcheckController extends Controller
{
     
    public function showHomePage(){
        if (session()->has('companyID')){
            $arrayDetails = Session::get('spotcheckData');
            //$arrayDetails['records'] =[];
            return view('mobilespotcheck.pages.homepage')->with($arrayDetails);
        }else{
            return redirect()->route('compliancelogin');
        }
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
        
        //Log::error($count);
        
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
