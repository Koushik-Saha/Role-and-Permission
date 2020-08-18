<?php

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


// Route url
Route::get('/', 'DashboardController@dashboardAnalytics');

// Route Authenticated
Route::middleware('auth')->group(function () {
// Route Projects
    /*Route::prefix('project')->group(function () {

        Route::get('/add-project', 'ProjectsController@addProject')->name('projects-add-project');
        Route::post('/add-project', 'ProjectsController@processAddProject')->name('projects-add-project');

        Route::get('/active-project-list', 'ProjectsController@activeProjectList')->name('active-project-list');
        Route::get('/hold-project-list', 'ProjectsController@holdProjectList')->name('hold-project-list');
        Route::get('/completed-project-list', 'ProjectsController@completedProjectList')->name('completed-project-list');
        Route::get('/cancelled-project-list', 'ProjectsController@cancelledProjectList')->name('cancelled-project-list');
        Route::get('/all-project-list', 'ProjectsController@allProjectList')->name('all-project-list');

        Route::get('/project-details/{id}', 'ProjectsController@projectDetails')->name('project-details');
        Route::post('/assign/{id}', 'ProjectLogsController@assignToProject')->name('project.assign');

        Route::get('/edit/{id}', 'ProjectsController@edit')->name('project-edit');
        Route::post('/edit/{id}', 'ProjectsController@update')->name('project-edit');

        Route::patch('/change-status', 'ProjectsController@changeStatus')->name('project-change-status');

    });
// Route Client
    Route::prefix('client')->group(function () {

        Route::get('/add-client', 'ClientController@addClient')->name('add-client');
        Route::post('/add-client', 'ClientController@processAddClient')->name('add-client');

        Route::get('/client-list', 'ClientController@clientList')->name('client-list');

        Route::get('/client-details/{id}', 'ClientController@clientDetails')->name('client-details');

        Route::get('/edit/{id}', 'ClientController@edit')->name('client-edit');
        Route::post('/edit/{id}', 'ClientController@update')->name('client-edit');

    });

    // Route Vendor
    Route::prefix('vendor')->group(function () {

        Route::get('/add-vendor', 'VendorController@addVendor')->name('add-vendor');
        Route::post('/add-vendor', 'VendorController@processAddVendor')->name('add-vendor');

        Route::get('/vendor-list', 'VendorController@vendorList')->name('vendor-list');

        Route::get('/vendor-details/{id}', 'VendorController@vendorDetails')->name('vendor-details');

        Route::get('/edit/{id}', 'VendorController@edit')->name('vendor-edit');
        Route::post('/edit/{id}', 'VendorController@update')->name('vendor-edit');

    });

    // Route Vendor
    Route::prefix('administrator')->group(function () {

        Route::get('/add-administrator', 'AdministratorController@addAdministrator')->name('add-administrator');
        Route::post('/add-administrator', 'AdministratorController@processAddAdministrator')->name('add-administrator');

        Route::get('/administrator-list', 'AdministratorController@administratorList')->name('administrator-list');

        Route::get('/administrator-details/{id}', 'AdministratorController@administratorDetails')->name('administrator-details');

        Route::get('/edit/{id}', 'AdministratorController@edit')->name('administrator-edit');
        Route::post('/edit/{id}', 'AdministratorController@update')->name('administrator-edit');

    });

    //Working Shift Routes
    Route::prefix('working-shift')->group(function () {
        Route::get('/add-working-shift', 'WorkingShiftController@addWorkingShift')->name('add-working-shift');
        Route::post('/add-working-shift', 'WorkingShiftController@processWorkingShift')->name('add-working-shift');
        Route::post('/working-shift-list', 'WorkingShiftController@workingShiftList')->name('working-shift-list');
        Route::delete('/delete', 'WorkingShiftController@delete')->name('shift.delete');
    });*/

    //Working Shift Routes
    Route::prefix('manpower')->group(function () {
        Route::get('/add-manpower', 'ManPowerController@addManPower')->name('add-manpower');
        Route::post('/add-manpower', 'ManPowerController@processManPower')->name('add-manpower');

        Route::get('/add-designation', 'ManPowerController@addDesignation')->name('add-designation');
        Route::post('/add-designation', 'ManPowerController@processDesignation')->name('add-designation');
        Route::delete('/delete-designation', 'ManPowerController@deleteDesignation')->name('delete-designation');

        Route::get('/staff-list', 'ManPowerController@staffList')->name('staff-list');
        Route::post('/search-staff', 'ManPowerController@searchStaff')->name('manpower-search_staff');
        Route::get('/change-staff-status', 'ManPowerController@changeStaffStatus')->name('change-staff-status');

        Route::get('/staff-attendance', 'ManPowerController@staffAttendance')->name('manpower-staff-attendance');
        Route::post('/search-attendance', 'ManPowerController@searchAttendance')->name('manpower-search-attendance');

        Route::get('/monthly-index', 'ManPowerController@monthlyIndex')->name('manpower-monthly');
        Route::post('/salary-report', 'ManPowerController@salaryReport')->name('manpower-salary-report');

        Route::get('/manpower-details/{project}/{id}', 'ManPowerController@manpowerDetails')->name('manpower-details');

        Route::post('/pay', 'ManPowerController@pay')->name('manpower-pay');

        Route::prefix('attendance')->group(function () {
            Route::post('/store', 'AttendanceController@storeAttendance')->name('manpower-store-attendance');

            Route::get('/report', 'AttendanceController@report')->name('manpower-attendance-report');
            Route::post('/report', 'AttendanceController@showReport')->name('manpower-attendance-report');

        });
    });

    /*//Accountant
    Route::prefix('accounts')->group(function () {

        Route::get('/index', 'BankAccountController@index')->name('bank-index');
        Route::post('/add-account', 'BankAccountController@storeAccount')->name('add-bank-account');

        Route::post('/transfer-to-employee', 'BankAccountController@transferToEmployee')->name('bank-transfer-to-employee');

        Route::post('/transfer-to-office', 'BankAccountController@transferToOffice')->name('bank-transfer-to-office');

        Route::post('/withdraw-from-bank', 'BankAccountController@withdrawFromBank')->name('withdraw-from-bank');
        Route::post('/deposit-to-bank', 'BankAccountController@depositToBank')->name('deposit-to-bank');

        Route::post('recharge-from-client', 'BankAccountController@rechargeFromCustomer')->name('recharge-from-client');

        Route::get('/bank-details/{id}', 'BankAccountController@bankDetails')->name('bank-show');


        Route::post('/get-bank-users', 'BankAccountController@getUsers')->name('bank-users-search');
        Route::post('/get-bank-projects', 'BankAccountController@getManagers')->name('bank-project-manager-search');
        Route::post('/get-bank-client-projects', 'BankAccountController@getClientProjects')->name('bank-client-project-search');

        Route::get('/income', 'BankAccountController@income')->name('bank-income');
        Route::get('/expense', 'BankAccountController@expense')->name('bank-expense');
    });

    // Loans Route
    Route::prefix('loans')->group(function () {
        Route::get('/', 'LoanController@index')->name('loan-index');

        Route::post('/new', 'LoanController@storeLoan')->name('loan-new');
        Route::post('/pay', 'LoanController@payLoan')->name('loan-pay');

        Route::get('/show/{id}', 'LoanController@show')->name('loan-show');

        Route::get('/pay/banks', 'LoanController@bankAccounts')->name('loan-banks');
    });*/

});


Auth::routes();

//Route::post('/login/validate', 'Auth\LoginController@validate_api');

//Route::get('/test-filemanager', 'ProjectsController@processfilemanager')->name('projects-filemanager');
