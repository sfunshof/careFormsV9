<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    
    protected $company_settings;
    public function  __construct(){
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $companyID=-1;
            if (Auth::user()){
                $companyID= Auth::user()->id;
            }
            //take this from authentication
            $this->company_settings= DB::select("select * from companyprofiletable where companyID=? ", [$companyID]);
            View::share('company_settings', $this->company_settings);
            return $next($request);
        });
           
    }
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
