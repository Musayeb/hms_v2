<?php

use App\Http\Controllers\CompanyBillInfo1Controller;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

// use Illuminate\Support\Facades\Storage;


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
Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
->middleware(['auth'])
->name('verification.notice');

    Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
        ->middleware(['auth'])
        ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
    Route::get('/','HomeController@index1');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');



Route::group(['middleware' => 'auth:sanctum','verified'], function () {
Route::group(['middleware' => ['verified']], function () {

Route::get('/dashboard','HomeController@index');


// patients
Route::resource('patients','patient\PatientsController');
Route::post('patients_update','patient\PatientsController@update');
// end patients
// department
Route::resource('departments','hr\DepartmentsController');
Route::post('departments_update','hr\DepartmentsController@update');
// end department 

// // Position
// Route::resource('positions','hr\PositionController');
// Route::post('positions_update','hr\PositionController@update');
// // end Position 

// employee
Route::resource('employees','hr\EmployeeController');
Route::get('employees/create/option','hr\EmployeeController@regoption');
Route::get('employees/create/docter','hr\EmployeeController@docterCreate');


Route::post('employees_update','hr\EmployeeController@update');
Route::get('employees/doc/download/{id?}/{type?}','hr\EmployeeController@download');
// 
Route::resource('payroll','hr\PayrollController');
Route::get('payroll_print/{id?}','hr\PayrollController@print');
Route::get('payroll_edit_second/{id?}','hr\PayrollController@payroll_edit_second');
Route::post('payroll_edit','hr\PayrollController@payroll_edit');


Route::get('delete_payroll_detail_record/{id}','hr\PayrollController@delete_payroll_detail_record');

Route::post('payroll_status','hr\PayrollController@status_change');
Route::post('payroll_update','hr\PayrollController@update');
// 

// appoinments
Route::resource('appoinments','patient\AppoinmentsController');
Route::get('appoinments_get_filter/{id?}','patient\AppoinmentsController@getfilter');
Route::get('appoinments_get_print/{id?}','patient\AppoinmentsController@print');




Route::get('appoinments_get_position/{id?}','patient\AppoinmentsController@getpostition');
Route::get('get_test_via_department/{id?}','patient\AppoinmentsController@get_test_dep');
Route::get('get_test_fee/{id?}','patient\AppoinmentsController@get_test_fee');

// room

// end

Route::get('getDocterFees/{id?}','patient\AppoinmentsController@getdocterfee');
Route::post('appoinments_update','patient\AppoinmentsController@update');
// appoinments

//opd
Route::resource('opd','patient\OpdController');
Route::get('opd/bill/print/{type?}/{id?}','patient\OpdController@print');
Route::get('opd/return_fees/{id?}/{type?}','patient\OpdController@return_fees');
Route::post('opd_update','patient\OpdController@update');
Route::post('app_serach','patient\OpdController@serach');
Route::post('createRevisitRecord','patient\OpdController@revisitcreate');
Route::post('createtestRecord','patient\OpdController@testcreate');
Route::get('opdEditvisit/{id?}','patient\OpdController@getEditData');
Route::post('Updateopdvist','patient\OpdController@updateopdvisit');
Route::get('opdEdittest/{id?}','patient\OpdController@getTestEditData');
Route::post('Updateopdtest','patient\OpdController@updateopdtest');
Route::get('printOPDFirst/{id?}','patient\OpdController@printOPDFirst');


// opd 
Route::resource('test','TestController');
Route::post('test_update','TestController@update');
// createRevisitRecord


// stock

// main Catagory pharmasi
Route::resource('medicines_cat','hr\PharmaMainCatagoryController');
Route::post('medicines_cat_update','hr\PharmaMainCatagoryController@update');
// main Catagory pharmasi

Route::resource('medicines','hr\MidicinesController');
Route::get('search_medicine_name','hr\MidicinesController@serach_medicine_name');
Route::get('add_medicine_detail/{id?}','hr\MidicinesController@add_medicine_detail');




Route::get('medicines/barcode/{id?}','hr\MidicinesController@medicine_barcode');
Route::post('medicines_update','hr\MidicinesController@update');
Route::resource('purchase-mediciens','hr\PurchaseMidicinesController');
Route::post('purchase-mediciens-update','hr\PurchaseMidicinesController@update');


Route::get('medicineFiter/{id?}','hr\PurchaseMidicinesController@filter');
// 
// laboratory
Route::resource('lab-cat','hr\LabCatagoryController');
Route::post('lab_cat_update','hr\LabCatagoryController@update');


Route::resource('lab-materials','hr\LabMaterialController');
Route::post('material_update','hr\LabMaterialController@update');


Route::resource('lab-purchase-materials','hr\PurchaselabMaterialController');
Route::post('materials_update','hr\PurchaselabMaterialController@update');

Route::get('material_filter/{id?}','hr\LabMaterialController@filter');


// suregery and delivery
Route::resource('surgery','hr\SurgeryController');
Route::post('surgery_update','hr\SurgeryController@update');

Route::resource('procedure','hr\ProcedureController');
Route::post('procedure_update','hr\ProcedureController@update');

Route::resource('surgery_registration','hr\PatientOperationController');
Route::post('surgery_registration_update','hr\PatientOperationController@update');
Route::get('operation_reg_docters/{dep_id?}/{type?}','hr\PatientOperationController@docter_reg_operate');
Route::get('create_surgery_record','hr\PatientOperationController@create_record');
Route::get('surgery_fees/{id?}','hr\PatientOperationController@surgery_fees');
Route::get('procedure_fees/{id?}','hr\PatientOperationController@procedure_fees');
Route::get('print_operation/{id?}','hr\PatientOperationController@print_bill');


//

// billing
// pharmacy
// Route::get('bill-pharmacy/fetch_data','hr\bill\PharmaBillController@fetch_data');
// search
Route::get('search_opd_record','hr\bill\PharmaBillController@search_opd_in_add_bill');
Route::get('search_patient_record','hr\bill\PharmaBillController@search_patient_in_add_bill');
// pharmacy second verison
Route::get('generate_bill_pharmacy','hr\bill\PharmaBillController@generate_bill');
Route::get('pharmacy_pos','hr\bill\PharmaBillController@pharmacy_pos');

// 
Route::resource('bill-pharmacy','hr\bill\PharmaBillController');
Route::get('bill-pharmacy/{id?}/update','hr\bill\PharmaBillController@update_view');
Route::get('serach_medicine_barcode/{barcode?}','hr\bill\PharmaBillController@barcode_serach');

Route::post('bill-pharmacy_update','hr\bill\PharmaBillController@update');
Route::get('getMedicine_info/{id?}','hr\bill\PharmaBillController@getMedicine');
Route::post('pharmacy_add_medicine_bill','hr\bill\PharmaBillController@addmedicine_to_bill');
// Route::get('bill_pharmacy_detail/{id?}','hr\bill\PharmaBillController@bill_pharmacy_detail');
Route::post('pharmacy-bill-discount','hr\bill\PharmaBillController@bill_pharmacy_discount');
Route::get('getMedicine_info_edit/{id?}','hr\bill\PharmaBillController@edit_medicine_info');
Route::post('pharmacy_update_medicine_bill','hr\bill\PharmaBillController@updatemedicine_to_bill');
Route::get('pharmacy_delete_medicine_bill/{id?}','hr\bill\PharmaBillController@deletemedicine_to_bill');
// end pharmacy

// print pharmacy bill

Route::get('bill_pharmacy_print/{id?}','hr\bill\PharmaBillController@bill_pharmacy_print');


// laboratory
Route::resource('bill-lab','hr\bill\LabBillController');
Route::post('bill-lab_update','hr\bill\LabBillController@update');
// point of sale lab
Route::get('laboratory_pos','hr\bill\LabBillController@lab_pos');
Route::get('get_all_test','hr\bill\LabBillController@get_tests');
Route::get('add_test_detail/{id?}','hr\bill\LabBillController@add_test_detail');
Route::get('bill-lab/{id?}/update','hr\bill\LabBillController@lab_bill_update');

Route::get('get_test_using_dep/{id?}','hr\bill\LabBillController@get_test_using_dep');
Route::get('gettest_info/{id?}','hr\bill\LabBillController@gettest_info');


Route::resource('bill_lab_info','hr\bill\LabBillInfoController');
Route::get('bill_lab_info_detail/{id?}','hr\bill\LabBillInfoController@bill_info_detail');
Route::get('getlab_info_edit/{id?}','hr\bill\LabBillInfoController@getlab_info_edit');

Route::post('lab_update_test_bill','hr\bill\LabBillInfoController@updatelab_info_edit');
Route::get('getsdocter/{id?}','hr\bill\LabBillController@getDocter');
Route::post('lab-bill-discount','hr\bill\LabBillController@discount');
// end lab

// room
Route::resource('room','setup\RoomController');
Route::post('room_update','setup\RoomController@update');

// end room
// admisssion
Route::resource('admission-bill',"hr\bill\AdmissionBillController");
Route::post('admission-bill_update',"hr\bill\AdmissionBillController@update");
Route::get('surger_data/{id?}',"hr\bill\AdmissionBillController@surgerygetdata");
Route::get('getRoomFees/{id?}','hr\bill\AdmissionBillController@getroomfees');
Route::post('add_room_to_bill','hr\bill\AdmissionBillController@addroomtobill');
Route::get('bill_add_info_detail/{id?}','hr\bill\AdmissionBillController@bill_info_detail');
Route::post('bill_add_charges','hr\bill\AdmissionBillController@bill_add_charges');
Route::post('bill_edit_charges','hr\bill\AdmissionBillController@bill_edit_charges');
Route::get('bill_edit_charges/edit/{id?}','hr\bill\AdmissionBillController@bill_edit_charges_edit');
Route::get('bill_delete_charges/{id?}','hr\bill\AdmissionBillController@bill_delete_charges');
Route::post('admission-bill-discount','hr\bill\AdmissionBillController@admission_discount');
Route::get('admission-bill/discharge/{id?}',"hr\bill\AdmissionBillController@discharge");

// end admission
// overtime
Route::resource('over_time_payment','hr\bill\OvertimePayController');
Route::post('over_time_payment_update','hr\bill\OvertimePayController@update');
// end overtime
// nurse bill
Route::resource('nurse_bill','hr\bill\NurseBillController');
Route::post('nurse_bill_update','hr\bill\NurseBillController@update');
Route::get('getnurse/{id?}','hr\bill\NurseBillController@getnurse');

// end of nurse
// companyBill
Route::resource('medical_company_bill','hr\bill\CompanyBillController');
Route::post('medical_company_bill_update','hr\bill\CompanyBillController@update');
// info
Route::resource('medical_company_bill_info','hr\bill\CompanyBillInfoController');
Route::post('medical_company_bill_info_update','hr\bill\CompanyBillInfoController@update');
Route::get('medical_company_bill_info/get/{id?}','hr\bill\CompanyBillInfoController@getinfo');
// 

Route::post('medical_company_bill_info1','hr\bill\CompanyBillInfoController@store1');
Route::get('medical_company_bill_info1_edit/{id?}','hr\bill\CompanyBillInfoController@edit1');
Route::post('medical_company_bill_info1update','hr\bill\CompanyBillInfoController@update1');
Route::get('medical_company_bill_info1delete/{id?}','hr\bill\CompanyBillInfoController@delete');



// endcompany
// finacial
Route::resource('finance/daily_expenses', 'finance\PettycashController');
Route::post('finance/daily_expenses_update', 'finance\PettycashController@update');
// 
// finacial statment
Route::get('finance/statment', 'finance\FinancialStatmentController@index');
Route::get('filter-financial-statment/{date?}', 'finance\FinancialStatmentController@filter');
// 

// finance Income
Route::resource('finance/extra-income', 'finance\ExtraIncomeController');
Route::post('finance/extra-income/update', 'finance\ExtraIncomeController@update');


// Partial Payment Billing
Route::resource('partial-payment-billing', 'hr\PartialPaymentBillingController');
Route::get('getPatient/{id?}', 'hr\PartialPaymentBillingController@getPatient');

Route::get('partial-payment-billing/print/{id?}', 'hr\PartialPaymentBillingController@print');
Route::post('part_p_b_update','hr\PartialPaymentBillingController@update');

// 

// End Of The Day
Route::resource('end-of-the-day', 'hr\EndOfTheDayController');
Route::post('end-of-the-day_update','hr\EndOfTheDayController@update');
Route::get('calculateEndoftheday','hr\EndOfTheDayController@calculate');


// 

// User Management===
Route::resource('users', 'hr\UserController');
Route::get('users_log', 'hr\UserController@log');
Route::post('user_update', 'hr\UserController@update');
Route::post('resetPassword', 'hr\UserController@reset_password');
// Permissions
Route::resource('permissions', 'hr\PermissionsController');
Route::post('permissions_update', 'hr\PermissionsController@update');
// Roles
Route::resource('roles', 'hr\RolesController');
Route::post('roles_update','hr\RolesController@update');
// Birth & Death Record
// Birth Record
Route::resource('birth-record', 'hr\BirthController');
Route::get('birth_certificate_print/{id?}', 'hr\BirthController@print');


Route::post('birth-record_update','hr\BirthController@update');
    // Death Record
Route::resource('death-record', 'hr\DeathController');
Route::get('death_certificate_print/{id?}', 'hr\DeathController@print');

Route::post('death-record_update','hr\DeathController@update');


// Blood Donation
Route::resource('blood-donation', 'hr\BloodDonationController');
Route::post('blood-donation_update','hr\BloodDonationController@update');
// 


// Filter 
Route::get('filter', 'HomeController@filter_show');
Route::post('filter-change', 'HomeController@filter_add');
Route::get('settings','HomeController@settings');
Route::get('deleteothersession','HomeController@deleteothersession');
Route::post('avatar-change','HomeController@avetar_change');
Route::post('setting/password', 'HomeController@password_change');
Route::post('setting/change_detail', 'HomeController@change_detail');
Route::get('connection', 'HomeController@connect');

// approval
Route::get('payroll_approval','HomeController@payrollApproval');
Route::post('pharmacy_approval','HomeController@pharmacyApproval');
Route::get('purchase-mediciens_aproval','HomeController@medicineaproval');
Route::post('purchase-mediciens_aproval_status','HomeController@medicinechangeStatus');
Route::get('purchase-lab_aproval','HomeController@labaproval');
Route::post('purchase-lab_aproval_status','HomeController@labmaterialchangeStatus');
Route::get('dailyexpense_aproval','HomeController@dailyexpensesaproval');
Route::post('dailyexpense_aproval_status','HomeController@dailyexpenseschangeStatus');
// approval
// Route::get('email',function (){return view('Email.basic');});
    
//Report
Route::get('opd-report','HomeController@opd_report');
Route::get('filter-opd-report/{date?}/{docter?}','HomeController@opd_report_filter');

Route::get('pharmacy-report','HomeController@pharmacy_report');
Route::get('filter-pharmacy-report/{date?}/','HomeController@pharmacy_report_filter');

Route::get('laboratory-report','HomeController@lab_report');
Route::get('filter-lab-report/{date?}/','HomeController@lab_report_filter');




// report
});
});
