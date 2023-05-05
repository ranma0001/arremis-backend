<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\NetworkDealersController;
use App\Http\Controllers\ServiceCenterController;
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

Route::group(['middleware' => 'api'], function ($router) {

    //pagination

    //Login
    Route::post('/auth/register', [JWTController::class, 'register']);
    Route::post('/auth/login', [JWTController::class, 'login']);
    Route::post('/auth/logout', [JWTController::class, 'logout']);
    Route::post('/auth/refresh', [JWTController::class, 'refresh']);
    Route::post('/auth/user_info', [JWTController::class, 'profile']);

    //Applicant
    Route::post('/add_applicant', [ApplicationController::class, 'create_applicant']);
    // Route::delete('applicants/{id}/delete', [ApplicationController::class, 'destroy']);
    Route::put('applicants/delete/{id}', [ApplicationController::class, 'edit_is_delete']); //soft delete
    Route::put('applicants/update/{id}', [ApplicationController::class, 'update_applicant']);
    Route::get('applicants/show/{id}', [ApplicationController::class, 'show']);

    //Facility
    Route::post('applicants/add_facility', [FacilityController::class, 'create_facility']);
    Route::put('applicants/delete_facility/{id}', [FacilityController::class, 'edit_is_delete']);
    Route::get('applicants/show_applicant/{id}', [FacilityController::class, 'show']);
    Route::get('applicants/all_facility', [FacilityController::class, 'view_facility']);

    //Equipment
    Route::post('applicants/add_equipment', [EquipmentController::class, 'create_equipment']);
    Route::put('applicants/delete_equipment/{id}', [EquipmentController::class, 'edit_is_delete']);
    Route::get('applicants/all_equipment', [EquipmentController::class, 'view_facility']);

    //Network Dealer
    Route::post('applicants/add_network_dealer', [NetworkDealersController::class, 'create_network_dealers']);
    Route::put('applicants/delete_network_dealer/{id}', [NetworkDealersController::class, 'edit_is_delete']);

    //Service Center
    Route::post('applicants/add_service_center', [ServiceCenterController::class, 'create_service_center']);
    Route::put('applicants/delete_service_center/{id}', [ServiceCenterController::class, 'edit_is_delete']);

});
