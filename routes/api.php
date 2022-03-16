<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['return.json'])->group(function() {
    Route::post('login', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'login']);
    Route::post('refresh', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'refresh']);
    Route::post('valid', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'valid'])->middleware(['auth.custom:access_token']);
    Route::post('logout', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'logout']);
    Route::post('device_token/{account}', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'addDeviceToken']);

});

Route::middleware(['return.json','auth.custom:access_token'])->group(function() {
    Route::get('patients', [\App\Http\Controllers\Mobile\PatientControllerMobile::class, 'index'])->middleware(['pagination.init','convert.datetime']);
    Route::post('patients/search', [\App\Http\Controllers\Mobile\PatientControllerMobile::class, 'searchPatientNameSocial']);

    Route::get('patients/{patient}/has_report', [\App\Http\Controllers\Mobile\DailyReportControllerMobile::class, 'hasReport']);
    Route::get('patients/{patient}/reports', [\App\Http\Controllers\Mobile\DailyReportControllerMobile::class, 'getAllPatientReports'])->middleware(['pagination.init','convert.datetime']);
    Route::get('patients/{patient}/reports/{daily_report}', [\App\Http\Controllers\Mobile\DailyReportControllerMobile::class, 'getPatientReport'])->middleware(['convert.datetime']);
    Route::post('patients/{patient}/reports', [\App\Http\Controllers\Mobile\DailyReportControllerMobile::class, 'store']);

    Route::get('notifications/{account}', [\App\Http\Controllers\Mobile\NotificationControllerMobile::class, 'index'])->middleware(['pagination.init','convert.datetime']);;

    Route::get('reports/questions', [\App\Http\Controllers\DailyReportQuestionController::class, 'index']);

    Route::get('device_tokens', [\App\Http\Controllers\Mobile\LoginControllerMobile::class, 'showTokens']);

});
