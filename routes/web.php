<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\mobileController;
use App\Http\Controllers\backofficeController;
use App\Http\Controllers\formsController;
use App\Http\Controllers\utilityController;
use App\Http\Controllers\serviceUserController;
use App\Http\Controllers\employeeController; 
use App\Http\Controllers\homeController; 
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [homeController::class, 'index']);

//** Auth */


/*
Route::get('/register', function () {
    return redirect('/#register');
});

Route::get('/login', function () {
    return redirect('/#login');
});
*/

Route::get('{unique_value}', [mobileController::class, 'index']);
Route::post("user/save_feedback", [mobileController::class, 'save_userFeedback']);
Route::get("user/successSaved/{companyID}", [mobileController::class, 'successSaved']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("backoffice/dashboard", [backofficeController::class, 'show_dashboard'])->middleware(['auth','verified']);

    Route::get("serviceUser/addnew", [serviceUserController::class, 'addnew_serviceUser']);
    Route::post("serviceUser/save", [serviceUserController::class, 'save_serviceUser']);
    Route::post("serviceUser/disable", [serviceUserController::class, 'disable_serviceUser']);
    Route::post("serviceUser/enable", [serviceUserController::class, 'enable_serviceUser']);
    Route::get("serviceUser/browse", [serviceUserController::class, 'browse_serviceUsers']);
    Route::get("serviceUser/browse_all", [serviceUserController::class, 'browse_all_serviceUsers']);
    Route::get("serviceUser/browse/{pageNo}", [serviceUserController::class, 'browse_serviceUsers']);
    Route::post("serviceUser/get_details", [serviceUserController::class, 'get_serviceUserDetails']);
    Route::get("serviceUser/browse_surveyfeedback", [serviceUserController::class, 'browse_surveyFeedback_serviceUser']);
    Route::get("serviceUser/browse_surveyfeedback/{month}/{year}/{pageNo}", [serviceUserController::class, 'browse_surveyFeedback_serviceUser']);

    Route::get("employee/addnew", [employeeController::class, 'addnew_employee']);
    Route::post("employee/save", [employeeController::class, 'save_employee']);
    Route::post("employee/disable", [employeeController::class, 'disable_employee']);
    Route::post("employee/enable", [employeeController::class, 'enable_employee']);
    Route::get("employee/browse", [employeeController::class, 'browse_employees']);
    Route::get("employee/browse_all", [employeeController::class, 'browse_all_employees']);
    Route::get("employee/browse/{pageNo}", [employeeController::class, 'browse_employees']);
    Route::post("employee/get_details", [employeeController::class, 'get_employeeDetails']);
    Route::get("employee/browse_surveyfeedback", [employeeController::class, 'browse_surveyFeedback_employee']);
    Route::get("employee/browse_surveyfeedback/{month}/{year}/{pageNo}", [employeeController::class, 'browse_surveyFeedback_employee']);

    Route::get("user/view_feedback/{userID}/{unique_value}/{responseTypeID}", [formsController::class, 'view_feedback'])->where(['userID'=>'[0-9]+',  'responseTypeID'=>'[0-9]+']);

    Route::get("buildforms/serviceUserFeedback", [formsController::class, 'build_serviceUserFeedback']);
    Route::get("buildforms/employeeFeedback", [formsController::class, 'build_employeeFeedback']);
    Route::post("buildforms/update_form", [formsController::class, 'update_form']);

    Route::post("utility/user_sendsms", [utilityController::class, 'user_sendSMS']);
    Route::post("utility/serviceuser_viewresponse", [utilityController::class, 'serviceuser_viewResponse']);

    Route::get("backoffice/companyprofile", [backofficeController::class, 'show_companyProfile']);
    Route::post("backoffice/upate_companyprofile", [backofficeController::class, 'update_companyProfile']);
});

//Route::get("serviceUser/show_complaints", [serviceUserController::class, 'show_complaints_serviceUser']);
//Route::get("serviceUser/show_compliments", [serviceUserController::class, 'show_compliments_serviceUser']);

