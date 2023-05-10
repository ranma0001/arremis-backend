<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\NetworkDealersController;
use App\Http\Controllers\ProductListingController;
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
    Route::post('applicant', [ApplicationController::class, 'create_applicant']);
    // Route::delete('applicants/{id}/delete', [ApplicationController::class, 'destroy']);
    Route::put('applicant/archive/{id}', [ApplicationController::class, 'edit_is_delete']); //soft delete
    Route::put('applicant/{id}', [ApplicationController::class, 'update_applicant']);
    Route::get('applicant/{id}', [ApplicationController::class, 'show']);
    Route::get('applicants', [ApplicationController::class, 'list_applicant']);

    //Facility
    Route::post('facility', [FacilityController::class, 'create_facility']);
    Route::put('facility/archive/{id}', [FacilityController::class, 'edit_is_delete']);
    Route::get('facility/{id}', [FacilityController::class, 'show']);
    Route::get('facilities', [FacilityController::class, 'list_facility']);

    //Equipment
    Route::post('equipment', [EquipmentController::class, 'create_equipment']);
    Route::put('equipment/archive/{id}', [EquipmentController::class, 'edit_is_delete']);
    Route::get('equipments', [EquipmentController::class, 'list_equipment']);

    //Network Dealer
    Route::post('network_dealer', [NetworkDealersController::class, 'create_network_dealers']);
    Route::put('network_dealer/archive/{id}', [NetworkDealersController::class, 'edit_is_delete']);
    Route::get('network_dealers', [NetworkDealersController::class, 'list_network_dealer']);

    //Service Center
    Route::post('service_center', [ServiceCenterController::class, 'create_service_center']);
    Route::put('service_center/archive/{id}', [ServiceCenterController::class, 'edit_is_delete']);
    Route::put('service_center/{id}', [ServiceCenterController::class, 'update_service_center']);
    Route::get('service_centers', [ServiceCenterController::class, 'list_service_center']);

    //Product Listing
    Route::post('product_listing', [ProductListingController::class, 'create_product_listing']);
    Route::get('product_listings', [ProductListingController::class, 'list_product_listing']);
});
