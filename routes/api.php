<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NetworkDealersController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductListingController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ServiceCenterController;
use App\Http\Controllers\UserManagementController;
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
    Route::post('applicant', [ApplicantController::class, 'create_applicant']);
    Route::put('applicant/archive/{id}', [ApplicantController::class, 'edit_is_delete']); //soft delete
    Route::put('applicant/{id}', [ApplicantController::class, 'update_applicant']);
    Route::get('applicant/{id}', [ApplicantController::class, 'show']);
    Route::get('applicants', [ApplicantController::class, 'list_applicant']);

    //Application
    Route::post('application', [ApplicationController::class, 'create_application']);
    Route::put('application', [ApplicationController::class, 'update_application']);
    Route::get('applications', [ApplicationController::class, 'list_application_with_data']);
    Route::get('application', [ApplicationController::class, 'list_application_for_card']);
    Route::put('application/status/{id}', [ApplicationController::class, 'update_status']);

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
    Route::put('product_listing/archive/{id}', [ServiceCenterController::class, 'edit_is_delete']);
    Route::get('product_listings', [ProductListingController::class, 'list_product_listing']);

    #### File Upload Response
    Route::post('/file', [FileController::class, 'upload_file']);
    Route::get('/file', [FileController::class, 'read_file']);

    ##email reset password
    Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.reset');
    Route::get('password/email', [ResetPasswordController::class, 'changePassword'])->name('reset-password');

    ##Location
    Route::get('/location/region', [LocationController::class, 'getRegion']);
    Route::get('/location/province', [LocationController::class, 'getProvince']);
    Route::get('/location/municipality', [LocationController::class, 'getMunicipality']);
    Route::get('/location/barangay', [LocationController::class, 'getBarangay']);

    ##Account Management
    Route::post('/account/update/{id}', [AccountController::class, 'updateAccount']);

    ##User Management
    Route::post('/user_management/approval/{id}', [UserManagementController::class, 'accountApproval']);

    ##Generate PDF
    Route::get('/generate-pdf', [PdfController::class, 'generatePDF']);

    ##Admin PTO List of table
    Route::get('list/submission', [AdminController::class, 'submissionList']);
    Route::get('list/review', [AdminController::class, 'reviewList']);
});
