<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class mileageController extends Controller
{
    private $distanceController;
    public function __construct()
    {
        $this->distanceController = new distanceController();
    }
   
    //
    public function  client_mileage(){
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(42);
        $data=$this->calculateMileage($today, $startDate);
       
        $date = Carbon::parse($today)->toDateString();
        $data['endDate']=$date;
        $data['startDate']=$startDate->toDateString();
        return view('backoffice.pages.client_mileage', compact('data'));
    }

    public function  reload_client_mileage(Request $req){
        $endDate=$req->input('endDate');
        $startDate = $req->input('startDate');
        
        $data=$this->calculateMileage($endDate, $startDate);
               
        $data['endDate']=$endDate;
        $data['startDate']=$startDate;

        $html_sum = view('backoffice.fakecomponents.client_mileage_sum_component', compact('data'))->render();
        $html_daily = view('backoffice.fakecomponents.client_mileage_daily_component', compact('data'))->render();        
        return response()->json(['html_sum' => $html_sum, 'html_daily' => $html_daily ]);
    }

    public function  admin_mileage(){
        //$companyID=$this->company_settings[0]->companyID;
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(42);
        $startDate = $startDate->toDateString();
        $endDate = Carbon::parse($today)->toDateString();
        $data['report']=$this->generateMileageReport($startDate, $endDate);
        
        $data['endDate']=$endDate;
        $data['startDate']=$startDate;
        return view('backoffice.pages.admin_mileage' ,  compact('data'));
    }
    
    public function  reload_admin_mileage(Request $req){
        $endDate=$req->input('endDate');
        $startDate = $req->input('startDate');
        $data['report']=$this->generateMileageReport($startDate, $endDate);
        
        $data['endDate']=$endDate;
        $data['startDate']=$startDate;
        $html = view('backoffice.fakecomponents.admin_mileage_sum_component', compact('data'))->render();
        return response()->json(['html' => $html]);
    }
    
    
    private function countElementPositions(array $array, $element): int
    {
        $occurrences = 0;
        foreach ($array as $key => $value) {
            if (strcasecmp($value, $element) === 0) {
                $occurrences++;
            }
        }
        return $occurrences;
    }
    public function update_dailyPostcodes(Request $req){
        $dailyDate = $req->input('dailyDate');
        $postCodes = $req->input('postCodes');
        $startDate = $req->input('startDate');
        $endDate = $req->input('endDate');
                
        $userID = Session::get('careWorkerLoginID');
        $companyID = Session::get('companyID');
        $officePostcode = Session::get('officePostcode');
        
        $officeVisit = $this->countElementPositions($postCodes, $officePostcode);
        
        // Define the attributes to match and the values to update/insert
        $attributes = [
            'userID' => $userID,
            'companyID' => $companyID,
            'dates' => $dailyDate,
        ];
        
        $values = [
            'jsonPostcodes' => json_encode($postCodes),
            'officeVisitCount' => $officeVisit,
        ];
        
        // Use updateOrInsert to either update or insert the record
        DB::table('mileagetable')->updateOrInsert($attributes, $values);
        
        $data = $this->calculateMileage($endDate, $startDate);
        $data['endDate'] = $endDate;
        $data['startDate'] = $startDate;
        
        $html = view('backoffice.fakecomponents.client_mileage_sum_component', compact('data'))->render();
        return response()->json(['html' => $html]);
    }




    public function calculateMileage($today, $startDate)
    {
        $userID = Session::get('careWorkerLoginID');
        $companyID=Session::get('companyID');
 
        // Fetch records from the mileagetable
        $records = DB::table('mileagetable')
            ->select('jsonPostcodes', 'officeVisitCount', 'dates')
            ->where('companyID', $companyID)
            ->where('userID', $userID)
            ->whereBetween('dates', [$startDate, $today])
            ->get();
        
        // Fetch records from the mileagetable for today
        $todayRecords = DB::table('mileagetable')
            ->select('jsonPostcodes')
            ->where('companyID', $companyID)
            ->where('userID', $userID)
            ->where('dates', $today)
            ->get(); 
        
        $dateArray = [];
        $countArray = [];
        $distanceArray = [];
        $endDateArray = [];
       
        $summary_distance=0;
        foreach ($records as $record) {
            $postcodes = json_decode($record->jsonPostcodes);
            $officeVisitCount = $record->officeVisitCount;
            $date = $record->dates;
            $count = count($postcodes) - $officeVisitCount;

            $dateArray[] = $date;
            $countArray[] = $count;

            // Calculate total distance for postcode pairs using DistanceController methods
            $totalDistance = $this->distanceController->calculateTotalDistance($postcodes);
            $distanceArray[] = $totalDistance;
            $summary_distance=$summary_distance+$totalDistance;
        }
        
         // Add today's postcodes to endDateArray
         foreach ($todayRecords as $todayRecord) {
            $postcodes = json_decode($todayRecord->jsonPostcodes);
            $endDateArray = array_merge($endDateArray, $postcodes);
        }

        $dataArray= [
            'dates' => $dateArray,
             'counts' => $countArray,
             'distances' => $distanceArray,
             'daily' => $endDateArray,
             'summary_distance' => $summary_distance
        ];
        $dataArray=$this->populate_date($dataArray,$startDate,$today); 
        return   $dataArray;   
    }

    public function set_dailyPostcodes(Request $req){
        $date = $req->input('date');
        $userID = Session::get('careWorkerLoginID');
        $companyID=Session::get('companyID');
        $officePostcode=Session::get('officePostcode');
        $result = DB::table('mileagetable')
        ->where('userID', $userID)
        ->where('companyID', $companyID)
        ->where('dates', $date)
        ->value('jsonPostcodes');
        
        if (is_null($result)) {
            $result = json_encode([$officePostcode]); // Set default value if result is null
        }
            
        $data['daily']=json_decode($result, true);
        $data['endDate']=$date;
        $data['office_postcode']=$officePostcode;
        
        $html = view('backoffice.fakecomponents.client_mileage_daily_component', compact('data'))->render();
        return response()->json(['html' => $html]);
          
    }    


    private function populate_date($data, $fromDate, $toDate){
        $from = Carbon::parse($fromDate);
        $to = Carbon::parse($toDate);
        
        $officePostcode=Session::get('officePostcode');

        $dateArray = $data['dates'];
        $countArray = $data['counts'];
        $distanceArray = $data['distances'];
        
        $newDates = [];
        $newCounts = [];
        $newDistances = [];
        

        for ($date = $from; $date->lte($to); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            if (in_array($formattedDate, $dateArray)) {
                $index = array_search($formattedDate, $dateArray);
                $newDates[] = $formattedDate;
                $newCounts[] = $countArray[$index];
                $newDistances[] = $distanceArray[$index];
            } else {
                $newDates[] = $formattedDate;
                $newCounts[] = 0;
                $newDistances[] = 0;
            }
        }

        return [
            'dates' => $newDates,
            'counts' => $newCounts,
            'distances' => $newDistances,
            'daily' => $data['daily'],
            'summary_distance' => $data['summary_distance'],
            'office_postcode'  => $officePostcode
        ];
    } 
    
    //***Admin starts here  */
    public function generateMileageReport($startDate, $lastDate)
    {
        $companyID = Session::get('companyID');

        // Step 1: Fetch mileage data
        $mileageData = DB::table('mileagetable')
            ->select('userID', 'jsonPostcodes', 'officeVisitCount')
            ->whereBetween('dates', [$startDate, $lastDate])
            ->where('companyID', $companyID)
            ->get();

        // Step 2: Calculate total distances and counts
        $userDistances = [];
        $userCounts = [];
        foreach ($mileageData as $record) {
            $postcodes = json_decode($record->jsonPostcodes, true);
            $distance = $this->distanceController->calculateTotalDistance($postcodes);
            $callCount = count($postcodes) - $record->officeVisitCount;
            
            if (!isset($userDistances[$record->userID])) {
                $userDistances[$record->userID] = 0;
                $userCounts[$record->userID] = 0;
            }
            $userDistances[$record->userID] += $distance;
            $userCounts[$record->userID] += $callCount;
        }

        // Step 3: Calculate mileage payments
        $userPayments = [];
        foreach ($userDistances as $userID => $totalDistance) {
            $payment = $this->distanceController->calculateMileagePayment($totalDistance);
            $userPayments[$userID] = [
                'totalDistance' => $totalDistance,
                'mileageCost' => $payment,
                'totalCount' => $userCounts[$userID]
            ];
        }

        // Step 4: Fetch employee details
        $employees = DB::table('employeedetailstable')
            ->select('userID', 'firstName', 'lastName')
            ->where('jobFunction', 0)
            ->where('companyID', $companyID)
            ->where('isDisable', 0)
            ->get();

        // Step 5: Prepare final report
        $report = [];
        foreach ($employees as $employee) {
            $report[] = [
                'userID' => $employee->userID,
                'firstName' => $employee->firstName,
                'lastName' => $employee->lastName,
                'totalDistance' =>$this->formatNumber($userPayments[$employee->userID]['totalDistance'] ?? 0),
                'mileageCost' =>  $userPayments[$employee->userID]['mileageCost'] ?? 0,
                'totalCount' => $userPayments[$employee->userID]['totalCount'] ?? 0
            ];
        }


        return $report;
    }
    public function getMileageData($userID, $startDate, $lastDate, $companyID)
    {
        $mileageData = DB::table('mileagetable')
            ->select('dates', 'jsonPostcodes', 'officeVisitCount')
            ->where('userID', $userID)
            ->where('companyID', $companyID)
            ->whereBetween('dates', [$startDate, $lastDate])
            ->get();
        $processedData = [];
        foreach ($mileageData as $record) {
            $postcodes = json_decode($record->jsonPostcodes, true);
            $distance = $this->distanceController->calculateTotalDistance($postcodes);
            $calls = count($postcodes) - $record->officeVisitCount;
            $processedData[] = [
                'dates' => $record->dates,
                'calls' => $calls,
                'distance' => $this->formatNumber($distance)
            ];
        }
        return $processedData;
    }

    public function admin_level1(Request $req){
        $userID = $req->input('userID');
        $endDate = $req->input('endDate');
        $startDate = $req->input('startDate');
        $companyID = Session::get('companyID');
        //$companyID = auth()->user()->company_id; // Assuming you're getting companyID from the authenticated user
        $dates = $this->getMileageData($userID, $startDate, $endDate, $companyID);
        //get the full name to be used in heading
        $employee = DB::table('employeedetailstable')
            ->select(DB::raw('concat(firstName," ", lastName) as fullName'))
            ->where('userID', $userID)
            ->where('companyID', $companyID)
            ->first();
        $fullName=$employee->fullName;
        $data['dates']=$dates;
        $heading="Daily mileage usage for " . $fullName;
        $html = view('backoffice.fakecomponents.admin_mileage_details_date_component', compact('data'))->render();
        return response()->json(['html' => $html, 'heading' => $heading]);

    }
    
    public function admin_level2(Request $req){
        // Fetch the record from MileageTable
        $userID = $req->input('userID');
        $dateX = $req->input('dateX');
        $companyID = Session::get('companyID');
        $mileageRecord = DB::table('mileagetable')
            ->selectRaw('json_extract(jsonPostcodes, "$[*]") as postcodes')
            ->where('dates', $dateX)
            ->where('companyID', $companyID)
            ->where('userID', $userID)
            ->first();

        if (!$mileageRecord) {
             return [];       //return response()->json(['error' => 'Record not found'], 404);
        }

        $postcodes = json_decode($mileageRecord->postcodes, true);
        $distances = [];

        // Generate pairs and get distances
        for ($i = 0; $i < count($postcodes) - 1; $i++) {
            $from = $postcodes[$i];
            $to = $postcodes[$i + 1];
            $distance = $this->distanceController->getDistanceBetweenPostcodes($from, $to);
            $distances[] = [
                'From' => $from,
                'To' => $to,
                'Distance' => $distance
            ];
        }

        $data['postcodes']=$distances;
        $heading="Postcode visit  for " . $dateX;
        $html = view('backoffice.fakecomponents.admin_mileage_details_postcodes_component', compact('data'))->render();
        return response()->json(['html' => $html, 'heading' => $heading]);

    }




    public function formatNumber($number) {
        // Round to 2 decimal places
        $formatted = number_format($number, 2);
    
        // Remove trailing zeros
        $formatted = rtrim($formatted, '0');
    
        // If the last character is a decimal point, remove it as well
        if (substr($formatted, -1) === '.') {
            $formatted = rtrim($formatted, '.');
        }
    
        return $formatted;
    }

}