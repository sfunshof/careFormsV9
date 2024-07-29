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
use App\Http\Controllers\mobilespotcheckController;
use App\Http\Controllers\mobilecomplianceController;
use App\Http\Controllers\mobileprospectController;
use App\Http\Controllers\mobilemileageController;
use App\Http\Controllers\mobilenightController;
use App\Http\Controllers\distanceController;
use App\Http\Controllers\mileageController;
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

Route::get('/manifest.json', function () {
    return response()->view('manifest')->header('Content-Type', 'application/json');
});


//This is the actual production one
if (env('APP_ENV') === 'production') {
    Route::domain('compliance.caretrail.co.uk')->group(function () {
        Route::get('/', [mobilecomplianceController::class, 'showLoginForm'])->name('compliancelogin');
    });
    Route::get('/{any}', function () {
        // Check if the path starts with '/compliance/mobile'
        if (request()->is('compliance/mobile') || request()->is('compliance/mobile/*')) {
            // If it does, don't redirect
            return null;
        }
        // Otherwise, redirect to root
        return redirect('/');
    })->where('any', '.*')->fallback();
} else if (env('APP_ENV') === 'local') {
    Route::get('/compliance/mobile', [mobilecomplianceController::class, 'showLoginForm'])->name('compliancelogin');
}

/** start of website exposed */
Route::get('/', [homeController::class, 'index']);
Route::get('/mileage', [homeController::class, 'mileagePage'])->name('mileage');
/** end of website mileage  */


//** Feedback */
Route::get('f/{unique_value}', [mobileController::class, 'index']);
Route::post("user/save_feedback", [mobileController::class, 'save_userFeedback']);
Route::get("user/successSaved/{companyID}", [mobileController::class, 'successSaved']);
//** End of feedback  */

Route::post('/get-distance', [distanceController::class, 'getDistances'])->name('postCodeDistance');


Route::post('/compliance/mobile', [mobilecomplianceController::class, 'login'])->name('complianceloginlogic');
Route::middleware(['mobileLoggedIn'])->group(function () {
    Route::get('/compliance/menu', [mobilecomplianceController::class, 'showMenuForm'])->name('compliancemenu');

    Route::get('/spotcheck/mobileHome', [mobilespotcheckController::class, 'showHomePage'])->name('spotcheckhome');
    Route::post('/spotcheck/mobileSave', [mobilespotcheckController::class, 'saveSpotCheckData'])->name('spotchecksave');
    Route:: post('/spotcheck/mobileHome', [backofficeController::class, 'show_mobile_spotcheck_data']);

    Route::get('/prospect/mobileHome', [mobileprospectController::class, 'showHomePage'])->name('prospecthome');
    Route:: post('/prospect/mobileSave', [serviceUserController::class, 'save_serviceUser'])->name('prospectsave');
    Route:: post('/prospect/mobileSubmit', [mobileprospectController::class, 'submit_prospect'])->name('prospectsubmit');

    Route::get('/mileage/mobileHome', [mobilemileageController::class, 'showHomePage'])->name('mileagehome');
    Route::post('/mileage/mobileSave', [mobilemileageController::class, 'saveMileageData'])->name('mileagesave');
    Route::post('/mileage/mobileGet', [mobilemileageController::class, 'getMileageData'])->name('getPostcodeFromDatabase');

    Route::get('/night/mobileHome', [mobilenightController::class, 'showHomePage'])->name('nighthome');
    Route::post('/night/mobileSave', [mobilenightController::class, 'saveNightData'])->name('nightsave');
});

//This is for the employee to respond to spotchecking by email
Route::post('/spotcheck/checksave', [employeeController::class, 'check_employee_spotCheck_save']);
Route::get('/spotcheck/{ranNo}', [employeeController::class, 'check_employee_spotCheck'])->where('ranNo', '.*');
// **** End employee verify spot check */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("backoffice/feedback_dashboard", [backofficeController::class, 'show_feedback_dashboard'])->middleware(['auth','verified']);
    Route::get("backoffice/spotcheck_dashboard", [backofficeController::class, 'show_spotcheck_dashboard'])->middleware(['auth','verified']);
    Route::post("backoffice/spotcheck_dashboard", [backofficeController::class, 'update_spotcheck_dashboard'])->middleware(['auth','verified']);

    
    //Route::get("/login", [backofficeController::class, 'show_feedback_dashboard'])->middleware(['auth','verified']);

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

    //prospect shares controllers with serviceuser
    Route::get("prospect/addnew", [serviceUserController::class, 'addnew_prospect']);
    Route::post("prospect/submit",[mobileprospectController::class, 'submit_prospect'])->name('submitprospect');
    Route::get("prospect/browse", [serviceUserController::class, 'browse_prospects']);
    Route::get("prospect/browse/{pageNo}", [serviceUserController::class, 'browse_prospects']);
    Route::post("prospect/get_details", [serviceUserController::class, 'get_prospectDetails']);
    Route::get("prospect/browse_all", [serviceUserController::class, 'browse_all_prospects']);
    Route::post("prospect/convert", [serviceUserController::class, 'convert_prospect']);
    Route::post("prospect/pdf_prospect", [serviceUserController::class, 'pdf_prospectDetails'])->name('printPdfProspect');

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
    Route::get("employee/browse_spotcheck", [employeeController::class, 'browse_employee_spotCheck']);
    Route::post("employee/browse_spotcheck", [employeeController::class, 'update_employee_spotCheck']);
    Route::post("employee/view_spotcheck", [employeeController::class, 'view_employee_spotCheck']);
    //edit is similar to view different from update
    Route::post("employee/edit_spotcheck", [employeeController::class, 'edit_employee_spotCheck']);
    Route::get("employee/pdf_spotcheck", [employeeController::class, 'pdf_employee_spotCheck']);
    Route::post("employee/save_spotcheck", [employeeController::class, 'save_employee_spotCheck']);
    Route::post("employee/email_spotcheck", [utilityController::class, 'email_employee_spotCheck']);

    Route::get("user/view_feedback/{userID}/{unique_value}/{responseTypeID}", [formsController::class, 'view_feedback'])->where(['userID'=>'[0-9]+',  'responseTypeID'=>'[0-9]+']);

    Route::get("buildforms/serviceUserFeedback", [formsController::class, 'build_serviceUserFeedback']);
    Route::get("buildforms/employeeFeedback", [formsController::class, 'build_employeeFeedback']);
    Route::get("buildforms/spotCheck", [formsController::class, 'build_spotcheck']);
    Route::get("buildforms/prospect", [formsController::class, 'build_prospect']); 
    Route::post("buildforms/update_form", [formsController::class, 'update_form']);
    Route::post("buildforms/reset_form", [formsController::class, 'reset_form']);

    Route::post("utility/user_sendsms", [utilityController::class, 'user_sendSMS']);
    Route::post("utility/serviceuser_viewresponse", [utilityController::class, 'serviceuser_viewResponse']);

    Route::get("backoffice/companyprofile", [backofficeController::class, 'show_companyProfile']);
    Route::post("backoffice/upate_companyprofile", [backofficeController::class, 'update_companyProfile']);
    
    Route::get("backoffice/admin/mileage", [mileageController::class, 'admin_mileage'])->name('adminMileage');
    Route::post("backoffice/admin/level1", [mileageController::class, 'admin_level1'])->name('adminLevel1');
    Route::post("backoffice/admin/level2", [mileageController::class, 'admin_level2'])->name('adminLevel2');
    Route::post("backoffice/reload_admin_mileage", [mileageController::class, 'reload_admin_mileage'])->name('reload_admin_mileage');

    Route::get("backoffice/client/mileage", [mileageController::class, 'client_mileage'])->name('clientMileage');
    Route::post("backoffice/client/summary_report_mileage", [mileageController::class, 'client_summary_mileage'])->name('clientSummaryReport');
    Route::post("backoffice/check_postcode_validity", [distanceController::class, 'validatePostcodes'])->name('check_postcodeValidity');
    Route::post("backoffice/update_daily_postcodes", [mileageController::class, 'update_dailyPostcodes'])->name('update_dailyPostcodes');
    Route::post("backoffice/set_daily_postcodes", [mileageController::class, 'set_dailyPostcodes'])->name('set_dailyPostcodes');
    Route::post("backoffice/reload_client_mileage", [mileageController::class, 'reload_client_mileage'])->name('reload_client_mileage');
});

//Route::get("serviceUser/show_complaints", [serviceUserController::class, 'show_complaints_serviceUser']);
//Route::get("serviceUser/show_compliments", [serviceUserController::class, 'show_compliments_serviceUser']);

