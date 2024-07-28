<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class Controller extends BaseController
{
    
    protected $company_settings;
    public function  __construct(){
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $companyID=-1;
            if (Auth::user()){
                //Backward intergration so thath employee can also login
                $is_admin=Auth::user()->is_admin;
                $userName= Auth::user()->email;
                if ($is_admin==1){
                    $companyID= Auth::user()->id;
                }else{
                    $companyID= Auth::user()->companyID;
                }
                Session::put('is_admin', $is_admin);
                Session::put('companyID', $companyID);
                Session::put('userName', $userName);

                 //*** Mileage starts here check if the user is careworker or admin
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
                //** mileage ends  */
            }
            //take this from authentication
            $this->company_settings= DB::select("select * from companyprofiletable where companyID=? ", [$companyID]);
            View::share('company_settings', $this->company_settings);
            return $next($request);
        });
           
    }
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
