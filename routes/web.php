<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', [\App\Http\Controllers\AccountsController::class,'index'])->name('accounts.index');

Route::get('/login', [\App\Http\Controllers\LoginController::class,'index']);
Route::post('/login', [\App\Http\Controllers\LoginController::class,'authenticate'])->name('login');
Route::get('/logout', [\App\Http\Controllers\LoginController::class,'logout']);

Route::get('/accounts/edit/{account}', [\App\Http\Controllers\AccountsController::class,'edit'])->name('accounts.edit');
Route::put('/accounts/update/{account}', [\App\Http\Controllers\AccountsController::class,'update'])->name('accounts.update');
Route::get('/accounts/create/{role}', [\App\Http\Controllers\AccountsController::class,'create'])->name('accounts.create');
Route::post('/accounts/{account}/change_password', [\App\Http\Controllers\AccountsController::class,'changePassword'])->name('accounts.change_password');
Route::post('/accounts/store', [\App\Http\Controllers\AccountsController::class,'store'])->name('accounts.store');

Route::delete('/accounts/destroy/{account}', [\App\Http\Controllers\AccountsController::class,'destroy'])->name('accounts.destroy');

Route::get('/accounts/doctors', [\App\Http\Controllers\AccountsController::class,'searchDoctors'])->name('doctors');
Route::get('/accounts/patients', [\App\Http\Controllers\AccountsController::class,'searchPatients'])->name('patients');

Route::get('/patients/daily_reports', [\App\Http\Controllers\DailyReportController::class,'index'])->name('daily_reports.patients');
Route::get('/patients/{patient}/daily_reports', [\App\Http\Controllers\DailyReportController::class,'patientReports'])->name('daily_reports.patients.reports');
Route::get('/patients/{patient}/daily_reports/{daily_report}', [\App\Http\Controllers\DailyReportController::class,'edit'])->name('daily_reports.patients.report');
Route::get('/patients/{patient}/create_daily_report', [\App\Http\Controllers\DailyReportController::class,'create'])->name('daily_reports.create');
Route::post('/patients/{patient}/daily_reports/store', [\App\Http\Controllers\DailyReportController::class,'store'])->name('daily_reports.store');
Route::delete('/patients/{patient}/daily_reports/{daily_report}', [\App\Http\Controllers\DailyReportController::class,'destroy'])->name('daily_reports.destroy');

Route::get('/accounts/notifications', [\App\Http\Controllers\NotificationController::class,'index'])->name('notifications.accounts');
Route::get('/accounts/{account}/notifications', [\App\Http\Controllers\NotificationController::class,'accountNotifications'])->name('notifications.accounts.list');

Route::get('/patients/cats', [\App\Http\Controllers\CatsController::class,'index'])->name('cats.patients');
Route::get('/patients/{patient}/cats', [\App\Http\Controllers\CatsController::class,'patientCats'])->name('cats.patients.list');
Route::get('/patients/{patient}/create_cat', [\App\Http\Controllers\CatsController::class,'create'])->name('cats.create');
Route::post('/patients/{patient}/cat/store', [\App\Http\Controllers\CatsController::class,'store'])->name('cat.store');
Route::get('/patients/{patient}/cat/{cat}', [\App\Http\Controllers\CatsController::class,'edit'])->name('cat.edit');
Route::put('/patients/{patient}/cat/{cat}', [\App\Http\Controllers\CatsController::class,'update'])->name('cat.update');
Route::delete('/patients/{patient}/cat/{cat}', [\App\Http\Controllers\CatsController::class,'destroy'])->name('cat.destroy');

Route::get('/patients/ccqs', [\App\Http\Controllers\CcqController::class,'index'])->name('ccqs.patients');
Route::get('/patients/{patient}/ccqs', [\App\Http\Controllers\CcqController::class,'patientCcqs'])->name('ccqs.patients.list');
Route::get('/patients/{patient}/create_ccq', [\App\Http\Controllers\CcqController::class,'create'])->name('ccqs.create');
Route::post('/patients/{patient}/ccq/store', [\App\Http\Controllers\CcqController::class,'store'])->name('ccq.store');
Route::get('/patients/{patient}/ccq/{ccq}', [\App\Http\Controllers\CcqController::class,'edit'])->name('ccq.edit');
Route::put('/patients/{patient}/ccq/{ccq}', [\App\Http\Controllers\CcqController::class,'update'])->name('ccq.update');
Route::delete('/patients/{patient}/ccq/{ccq}', [\App\Http\Controllers\CcqController::class,'destroy'])->name('ccq.destroy');

Route::get('/patients/ccis', [\App\Http\Controllers\CciController::class,'index'])->name('ccis.patients');
Route::get('/patients/{patient}/ccis', [\App\Http\Controllers\CciController::class,'patientCcis'])->name('ccis.patients.list');
Route::get('/patients/{patient}/create_cci', [\App\Http\Controllers\CciController::class,'create'])->name('ccis.create');
Route::post('/patients/{patient}/cci/store', [\App\Http\Controllers\CciController::class,'store'])->name('cci.store');
Route::get('/patients/{patient}/cci/{cci}', [\App\Http\Controllers\CciController::class,'edit'])->name('cci.edit');
Route::put('/patients/{patient}/cci/{cci}', [\App\Http\Controllers\CciController::class,'update'])->name('cci.update');
Route::delete('/patients/{patient}/cci/{cci}', [\App\Http\Controllers\CciController::class,'destroy'])->name('cci.destroy');

Route::get('/patients/readings', [\App\Http\Controllers\ReadingController::class,'index'])->name('readings.patients');
Route::get('/patients/{patient}/readings', [\App\Http\Controllers\ReadingController::class,'patientReadings'])->name('readings.patients.list');
Route::get('/patients/{patient}/create_reading', [\App\Http\Controllers\ReadingController::class,'create'])->name('readings.create');
Route::post('/patients/{patient}/reading/store', [\App\Http\Controllers\ReadingController::class,'store'])->name('reading.store');
Route::get('/patients/{patient}/reading/{reading}', [\App\Http\Controllers\ReadingController::class,'edit'])->name('reading.edit');
Route::put('/patients/{patient}/reading/{reading}', [\App\Http\Controllers\ReadingController::class,'update'])->name('reading.update');
Route::delete('/patients/{patient}/reading/{reading}', [\App\Http\Controllers\ReadingController::class,'destroy'])->name('reading.destroy');

Route::get('/patients/treatments', [\App\Http\Controllers\TreatmentController::class,'index'])->name('treatments.patients');
Route::get('/patients/{patient}/treatments', [\App\Http\Controllers\TreatmentController::class,'patientTreatments'])->name('treatments.patients.list');
Route::get('/patients/{patient}/create_treatment', [\App\Http\Controllers\TreatmentController::class,'create'])->name('treatments.create');
Route::post('/patients/{patient}/treatment/store', [\App\Http\Controllers\TreatmentController::class,'store'])->name('treatment.store');
Route::get('/patients/{patient}/treatment/{treatment}', [\App\Http\Controllers\TreatmentController::class,'edit'])->name('treatment.edit');
Route::put('/patients/{patient}/treatment/{treatment}', [\App\Http\Controllers\TreatmentController::class,'update'])->name('treatment.update');
Route::delete('/patients/{patient}/treatment/{treatment}', [\App\Http\Controllers\TreatmentController::class,'destroy'])->name('treatment.destroy');

Route::get('/patients/deaths', [\App\Http\Controllers\DeathController::class,'index'])->name('deaths.patients');
Route::get('/patients/create_death', [\App\Http\Controllers\DeathController::class,'create'])->name('deaths.create');
Route::post('/patients/death/store', [\App\Http\Controllers\DeathController::class,'store'])->name('death.store');
Route::get('/patients/{patient}/death/{death}', [\App\Http\Controllers\DeathController::class,'edit'])->name('death.edit');
Route::put('/patients/{patient}/death/{death}', [\App\Http\Controllers\DeathController::class,'update'])->name('death.update');
Route::delete('/patients/{patient}/death/{death}', [\App\Http\Controllers\DeathController::class,'destroy'])->name('death.destroy');


