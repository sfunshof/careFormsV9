<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class mobilenightController extends Controller
{
    //
    public function showHomePage(){
        if (session()->has('companyID')){
            $arrayDetails = Session::get('spotcheckData');
            return view('mobilenight.pages.homepage')->with($arrayDetails);
        }else{
            return redirect()->route('compliancelogin');
        }
    }
    

}
