<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\NetworkDealers;
use App\Models\ProductListing;
use App\Models\ServiceCenter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.jwt');
    }
    public function create_application(Request $request)
    {

        $tables = ['equipment', 'facility', 'network_dealer', 'service_center', 'product_listing'];
        $token = JWTAuth::parseToken();
        $payload = $token->getPayload();
        $applicant_id_ = $payload->get('applicant_id');

        $validator = $this->validateInput($request);

        if ($validator != null) {
            return $validator;
        }

        try {
            DB::beginTransaction();
            $application = Application::create([
                'applicant_id' => $applicant_id_,
                'company_id' => $payload->get('company_id'),
                'application_type' => $request->application_type,
                'application_status' => $request->input('application_status'),
            ]);

            foreach ($tables as $tablesData) {
                DB::table($tablesData)->where('application_id', $application->id)->delete();
            }

            $equipmentArray = $request->input('equipments');
            $facilityArray = $request->input('facilities');
            $networkdealerArray = $request->input('network_dealers');
            $serviceCenterArray = $request->input('service_centers');
            $productListingArray = $request->input('product_listings');

            $errorMessages = [
                'equipmentArray' => 'Invalid equipment data.',
                'facilityArray' => 'Invalid facility data.',
                'networkdealerArray' => 'Invalid network dealer data.',
                'serviceCenterArray' => 'Invalid service center data.',
                'productListingArray' => 'Invalid product listing data.',
            ];

            foreach ([$equipmentArray, $facilityArray, $networkdealerArray, $serviceCenterArray, $productListingArray] as $key => $value) {
                if (!is_array($value)) {
                    throw new \Exception($errorMessages[$key]);
                }
            }

            $createdEquipment = [];
            $createdFacility = [];
            $createdNetworkdealer = [];
            $createdCerviceCenter = [];
            $createdProductListing = [];

            foreach ($facilityArray as $facilityData) {

                $facility = Facility::create([
                    'application_id' => $application->id,
                    'facility_name' => $facilityData['facility_name'],
                    'facility_quantity' => $facilityData['facility_quantity'],
                ]);

                $createdFacility[] = $facility;
            }

            foreach ($equipmentArray as $equipmentData) {
                $equipment = Equipment::create([
                    'application_id' => $application->id,
                    'equipment_name' => $equipmentData['equipment_name'],
                    'equipment_quantity' => $equipmentData['equipment_quantity'],

                ]);

                $createdEquipment[] = $equipment;
            }

            foreach ($networkdealerArray as $networkdealerData) {
                $networkdealer = NetworkDealers::create([
                    'application_id' => $application->id,
                    'company_name' => $networkdealerData['company_name'],
                    'address' => $networkdealerData['address'],
                    'contact' => $networkdealerData['contact'],
                    'email_address' => $networkdealerData['email_address'],
                ]);

                $createdNetworkdealer[] = $networkdealer;
            }

            foreach ($serviceCenterArray as $serviceCenterData) {
                $serviceCenter = ServiceCenter::create([
                    'application_id' => $application->id,
                    'center_name' => $serviceCenterData['center_name'],
                    'address' => $serviceCenterData['address'],
                    'contact' => $serviceCenterData['contact'],
                    'email_address' => $serviceCenterData['email_address'],
                ]);

                $createdCerviceCenter[] = $serviceCenter;
            }

            foreach ($productListingArray as $productListingData) {
                $product_listing = ProductListing::create([
                    'application_id' => $application->id,
                    'item_name' => $productListingData['item_name'],
                    'item_brand' => $productListingData['item_brand'],
                    'description' => $productListingData['description'],
                    'country_manufacturer' => $productListingData['country_manufacturer'],
                    'classification' => $productListingData['classification'],
                ]);

                $createdProductListing[] = $product_listing;
            }

            DB::commit();

            return response()->json([
                'data' => $application,
            ],
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update_application(Request $request)
    {
        $tables = ['equipment', 'facility', 'network_dealer', 'service_center', 'product_listing'];

        $token = JWTAuth::parseToken();
        $payload = $token->getPayload();
        $applicant_id_ = $payload->get('applicant_id');

        $validator = $this->validateInput($request);

        if ($validator != null) {
            return $validator;
        }

        try {
            DB::beginTransaction();

            $application = Application::where('applicant_id', $applicant_id_)->first();
            $equipment = Equipment::where('application_id', $application->id)->get();
            return $equipment;

            if ($application != null) {
                $application->update([
                    'is_deleted' => $request->is_deleted,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    public function list_application_with_data(Request $request)
    {
        $query = Application::with(['equipment']);

        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);
        return response()->json([
            'applicant' => $response,

        ]);
    }

    private function validateInput(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // //Facility VALIDATION
            'facilities.*.facility_name' => 'required|string|min:2|max:100',
            'facilities.*.facility_quantity' => 'required|integer',
            //Equipment VALIDATION
            'equipments.*.equipment_name' => 'required|string|min:2|max:100',
            'equipments.*.equipment_quantity' => 'required|integer',
            //Network VALIDATION
            'network_dealers.*.company_name' => 'required|string|min:2|max:100',
            'network_dealers.*.address' => 'required|string|min:2|max:100',
            'network_dealers.*.contact' => 'required|string|min:2|max:100',
            'network_dealers.*.email_address' => 'required|string|email|max:100',
            //Service Center VALIDATION
            'service_centers.*.center_name' => 'required|string|min:2|max:100',
            'service_centers.*.address' => 'required|string|min:2|max:100',
            'service_centers.*.contact' => 'required|string|min:2|max:100',
            'service_centers.*.email_address' => 'required|string|email|max:100',
            //Product Listing VALIDATION
            'product_listings.*.item_name' => 'required|string|min:2|max:100',
            'product_listings.*.item_brand' => 'required|string|min:2|max:100',
            'product_listings.*.description' => 'string|min:2|max:100',
            'product_listings.*.classification' => 'min:2|max:100',
        ]);

        $validator->setAttributeNames([
            'facilities.*.application_id' => 'Application ID',
            'facilities.*.facility_name' => 'Facility Name',
            'facilities.*.facility_quantity' => 'Quantity',
            'facilities.*.status' => 'Status',
            'facilities.*.review_comment' => 'Comment',
            'facilities.*.reviewed_by' => 'Reviewer',
            'facilities.*.is_verified' => 'Is_verified',

            'equipments.*.application_id' => 'Application ID ',
            'equipments.*.equipment_name' => 'Equipment Name',
            'equipments.*.equipment_quantity' => 'Quantity',
            'equipments.*.status' => 'Status',
            'equipments.*.review_comment' => 'Comment',
            'equipments.*.reviewed_by' => 'Reviewer',
            'equipments.*.is_verified' => 'Is_verified',

            'network_dealers.*.application_id' => 'Application ID',
            'network_dealers.*.company_name' => 'Company Name',
            'network_dealers.*.contact' => 'Contact',
            'network_dealers.*.address' => 'Address',
            'network_dealers.*.email_address' => 'Email Address',
            'network_dealers.*.status' => 'Status',
            'network_dealers.*.review_comment' => 'Comment',
            'network_dealers.*.reviewed_by' => 'Reviewer',
            'network_dealers.*.is_verified' => 'Is_verified',

            'service_centers.*.application_id' => 'Application ID',
            'service_centers.*.center_name' => 'Center Name',
            'service_centers.*.contact' => 'Contact',
            'service_centers.*.email_address' => 'Email Adress',
            'service_centers.*.address' => 'Address',
            'service_centers.*.longitude' => 'Longitude',
            'service_centers.*.latitude' => 'Latitude',
            'service_centers.*.review_comment' => 'Review Comment',
            'service_centers.*.reviewed_by' => 'Reviewed By',
            'service_centers.*.status' => 'Status',
            'service_centers.*.review_level' => 'Review Level',
            'service_centers.*.is_deleted' => 'Deleted',

            'product_listings.*.application_id' => 'Application ID',
            'product_listings.*.item_name' => 'Item Name',
            'product_listings.*.item_brand' => 'Item Brand',
            'product_listings.*.description' => 'Description',
            'product_listings.*.classification' => 'Classification',
            'product_listings.*.cc_no' => 'CC No.',
            'product_listings.*.country_manufacturer' => 'Country Manufacturer',
            'product_listings.*.inspected' => 'Inspected',
            'product_listings.*.review_comment' => 'Review Comment',
            'product_listings.*.reviewed_by' => 'Reviewed By',
            'product_listings.*.status' => 'Status',
            'product_listings.*.review_level' => 'Review Level',
            'product_listings.*.file_location' => 'File Location',
            'product_listings.*.file_name' => 'File Name',
            'product_listings.*.file_type' => 'File Type',
            'product_listings.*.is_deleted' => 'Deleted',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

    }

    private function getLastApplicantID()
    {

        $application = Application::query()
            ->select(DB::raw('max(application_id) as application_id'))
            ->where('is_deleted', 0)
            ->first();

        return $application_id = $application['application_id'] == null ? 0 : $application['application_id'];
    }
}
