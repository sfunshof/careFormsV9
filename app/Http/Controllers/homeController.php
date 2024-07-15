<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class homeController extends Controller
{
    //
    public function index(){
        return view('home.homepage');
    }
    public function mileagePage(){
        return view('mileage.homepage');
    }
}
