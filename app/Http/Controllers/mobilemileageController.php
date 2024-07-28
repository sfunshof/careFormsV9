<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class mobilemileageController extends Controller
{
    //
    public function showHomePage(){
        if (session()->has('companyID')){
            $arrayDetails = Session::get('spotcheckData');
            $countVisit=$this->countMileageVisit(Session::get('careWorkerLoginID')); 
            $arrayDetails['countVisit']=$countVisit;
            return view('mobilemileage.pages.homepage')->with($arrayDetails);
        }else{
            return redirect()->route('compliancelogin');
        }
    }
    
    public function countMileageVisit($userID){
        $today = now()->format('Y-m-d');
        $data = DB::table('mileagetable')
        ->selectRaw('JSON_LENGTH(jsonPostcodes) as postcodes_count, officeVisitCount')
        ->where('userID', $userID)
        ->whereDate('dates', $today)
        ->first();

        if (!$data) {
            return 0;
        }
        $count = $data->postcodes_count - $data->officeVisitCount;
        return $count >= 0 ? $count : 0;
    }
    
    public function saveMileageData(Request $req){
        // Retrieve data from the request
        if (session()->has('companyID')){
           $userID=Session::get('careWorkerLoginID');
           $officePostcode=Session::get('officePostcode');
           $today = Carbon::now()->format('Y-m-d');
           $companyID=Session::get('companyID');
           $tableName="mileagetable"; 
           $postCode=$req->postCode;
           $isLast=$req->isLast;
           
           $fieldSet = [
                'userID' => $userID,
                'companyID' => $companyID,
            ];
           
            $existingData = DB::table($tableName)
                ->where('userID', $userID)
                ->where('companyID', $companyID)
                ->whereDate('dates', $today)
                ->first();
            $result=null;
            if ($existingData) {
                $jsonPostCode = json_decode($existingData->jsonPostcodes, true);
                //isLast always does the update
                if ($isLast==1) $postCode=$officePostcode;

                $jsonPostCode[] = $postCode;
                $result=DB::table($tableName)
                    ->where('userID', $userID)
                    ->where('companyID', $companyID)
                    ->whereDate('dates', $today)
                    ->update([
                        'officeVisitCount' => DB::raw('officeVisitCount + ' . intval($isLast)),
                        'jsonPostcodes' => json_encode($jsonPostCode),
                    ]);
            } else {
                //islast can never install new postcode
                if ($isLast==0){
                    $firstPostcode=[$officePostcode, $postCode];
                    $result= DB::table($tableName)->insert([
                        'userID' => $userID,
                        'companyID' => $companyID,
                        'jsonPostcodes' => json_encode($firstPostcode),
                        'dates' => $today,
                    ]);
                }    
            }
                  
            $countVisit=$this->countMileageVisit($userID); 
            if ($result !== false) {
                // Operation successful
                return response()->json(['success'=>' Updated mileage table', 'status' => 1, 'countVisit' => $countVisit ]);
             } else {
                // Operation failed
                return response()->json(['failed'=>'  cannot update mileage table ', 'status' => 0, 'countVisit' => $countVisit]);
 
            }
                 }else{
            return redirect()->route('compliancelogin'); 
        }
    }
    public function getMileageData(Request $req){
        // Retrieve data from the request
        if (session()->has('companyID')){
           $userID=Session::get('careWorkerLoginID');
           $officePostcode=Session::get('officePostcode');
           $today = Carbon::now()->format('Y-m-d');
           $companyID=Session::get('companyID');
           $tableName="mileagetable"; 
          
           $data = DB::table($tableName)
               ->where('userID', $userID)
               ->where('companyID', $companyID)
               ->whereDate('dates', $today)
               ->first();
                   
           if ($data) {
               $jsonPostcodes = json_decode($data->jsonPostcodes, true); // Decode JSON to PHP array
           }else {
              // Handle case where no data found
               $jsonPostcodes = []; // Or return a default value
           }
           return response()->json(['success'=>' Updated mileage table', 'status' => 1, 'postCodeArray' =>$jsonPostcodes]);
        }else{
            return redirect()->route('compliancelogin'); 
        }    
    }
}    
