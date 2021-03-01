<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProviderController;
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

Route::apiResource('patient', PatientController::class);

Route::apiResource('provider', ProviderController::class);

Route::post('availability/provide', [ AvailabilityController::class, 'provide']);
Route::apiResource('availability', AvailabilityController::class);


Route::apiResource('appointment', AppointmentController::class);
//Route::group(['middleware' => ['api'], 'prefix' => 'api'], function() {
//Route::group([], function () {
//    Route::post('appointment', [AppointmentController::class, 'book']);
//    Route::delete('appointment', [AppointmentController::class, 'release']);
//});
//});


//Route::put('availability/book', 'AvailabilityController@book');

