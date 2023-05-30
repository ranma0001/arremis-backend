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

        $validator = $this->validateInput($request);

        if ($validator != null) {
            return $validator;
        }

        try {
            DB::beginTransaction();

            $application = Application::where('id', $request->application_id)->first();

            if ($application != null) {
                $application->update([
                    'application_status' => $request->application_status,
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

            }

            DB::commit();

            return response()->json([
                'applicant' => $application,
            ]);
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

    public function list_application_for_card()
    {
        $token = JWTAuth::parseToken();
        $payload = $token->getPayload();
        $companyId = $payload->get('company_id');

        $query = "
        WITH application_detail_count
        AS (
            SELECT
                applicant_raw_id
                , company_id
                , IFNULL(ud_application_id,'') application_id
                , status
                , MAX(equipment_count) AS equipment_count
                , MAX(facility_count) AS facility_count
                , MAX(network_dealer_count) AS network_dealer_count
                , MAX(service_center_count) AS service_center_count
                , MAX(product_listing_count) AS product_listing_count
            FROM (
                SELECT
                    A.raw_application_id_equipment AS applicant_raw_id
                    , A.equipment_count
                    , B.facility_count
                    , C.network_dealer_count
                    , D.service_center_count
                    , E.product_listing_count
                    , app.company_id
                    ,  app.application_id AS ud_application_id
                    , app.status
                FROM
                    (
                        SELECT
                            app.id AS raw_application_id_equipment
                            , COUNT(equip.application_id) AS equipment_count
                        FROM
                            application app
                        LEFT JOIN
                            equipment equip ON app.id = equip.application_id
                        WHERE
                            app.is_deleted = 0
                        AND
                            equip.is_deleted = 0
                        AND
                            app.company_id = " . $companyId . "
                        GROUP BY
                            app.id
                    ) A
                LEFT JOIN
                    (
                        SELECT
                            app.id AS raw_application_id_facility
                            , COUNT(fac.application_id) AS facility_count
                        FROM
                            application app
                        LEFT JOIN
                            facility fac ON app.id = fac.application_id
                        WHERE
                            app.is_deleted = 0
                        AND
                            fac.is_deleted = 0
                        AND
                            app.company_id = " . $companyId . "
                        GROUP BY
                            app.id
                        ) B
                    ON
                        A.raw_application_id_equipment = B.raw_application_id_facility
                LEFT JOIN
                    (
                        SELECT
                            app.id AS raw_application_id_network
                            , COUNT(net_deal.application_id) AS network_dealer_count
                        FROM
                            application app
                        LEFT JOIN
                            network_dealer net_deal ON app.id = net_deal.application_id
                        WHERE
                            app.is_deleted = 0
                        AND
                            net_deal.is_deleted = 0
                        AND
                            app.company_id = " . $companyId . "
                        GROUP BY
                            app.id
                        ) C
                    ON
                        A.raw_application_id_equipment = C.raw_application_id_network
                LEFT JOIN
                    (
                        SELECT
                            app.id AS raw_application_id_center
                            , COUNT(center.application_id) AS service_center_count
                        FROM
                            application app
                        LEFT JOIN
                            service_center center ON app.id = center.application_id
                        WHERE
                            app.is_deleted = 0
                        AND
                            center.is_deleted = 0
                        AND
                            app.company_id = " . $companyId . "
                        GROUP BY
                            app.id
                        ) D
                    ON
                        A.raw_application_id_equipment = D.raw_application_id_center
                LEFT JOIN
                    (
                        SELECT
                            app.id AS raw_application_id_product
                            , COUNT(prod_list.application_id) AS product_listing_count
                        FROM
                            application app
                        LEFT JOIN
                            product_listing prod_list
                        ON
                            app.id = prod_list.application_id
                        WHERE
                            app.is_deleted = 0
                        AND
                            prod_list.is_deleted = 0
                        AND
                            app.company_id = " . $companyId . "
                        GROUP BY
                            app.id
                        ) E
                    ON
                        A.raw_application_id_equipment = E.raw_application_id_product
                JOIN
                    application app
                ON
                    app.id = A.raw_application_id_equipment
                WHERE
                    app.is_deleted = 0
                AND
                    app.company_id = " . $companyId . "
            ) MAIN_T
            GROUP BY
                applicant_raw_id
                , company_id
        ),

        application_detail_comply AS (
        SELECT
            applicant_raw_id
            , company_id
            , IFNULL(MAX(equipment_count),0) AS equipment_comply_count
            , IFNULL(MAX(facility_count),0) AS facility_comply_count
            , IFNULL(MAX(network_dealer_count),0) AS network_comply_count
            , IFNULL(MAX(service_center_count),0) AS service_comply_count
            , IFNULL(MAX(product_listing_count),0) AS product_listing_comply_count
        FROM (
            SELECT
                A.raw_application_id_equipment AS applicant_raw_id
                , A.equipment_count
                , B.facility_count
                , C.network_dealer_count
                , D.service_center_count
                , E.product_listing_count
                , app.company_id
            FROM
                (
                    SELECT
                        app.id AS raw_application_id_equipment
                        , COUNT(equip.application_id) AS equipment_count
                    FROM
                        application app
                    LEFT JOIN
                        equipment equip ON app.id = equip.application_id
                    WHERE
                        app.is_deleted = 0
                        AND equip.is_deleted = 0
                        AND app.company_id = " . $companyId . "
                        AND equip.`status` = 0
                    GROUP BY
                        app.id
                ) A
            LEFT JOIN
                (
                    SELECT
                        app.id AS raw_application_id_facility
                        , COUNT(fac.application_id) AS facility_count
                    FROM
                        application app
                    LEFT JOIN
                        facility fac
                    ON
                        app.id = fac.application_id
                    WHERE
                        app.is_deleted = 0
                    AND
                        fac.is_deleted = 0
                    AND
                        app.company_id = " . $companyId . "
                    AND
                        fac.`status` = 0
                    GROUP BY
                        app.id
                    ) B
                ON
                    A.raw_application_id_equipment = B.raw_application_id_facility
            LEFT JOIN
                (
                    SELECT
                        app.id AS raw_application_id_network
                        , COUNT(net_deal.application_id) AS network_dealer_count
                    FROM
                        application app
                    LEFT JOIN
                        network_dealer net_deal
                    ON
                        app.id = net_deal.application_id
                    WHERE
                        app.is_deleted = 0
                    AND
                        net_deal.is_deleted = 0
                    AND
                        app.company_id = " . $companyId . "
                    AND
                        net_deal.`status` = 0
                    GROUP BY
                        app.id
                    ) C
                ON
                    A.raw_application_id_equipment = C.raw_application_id_network
            LEFT JOIN
                (
                    SELECT
                        app.id AS raw_application_id_center
                        , COUNT(center.application_id) AS service_center_count
                    FROM
                        application app
                    LEFT JOIN
                        service_center center
                    ON
                        app.id = center.application_id
                    WHERE
                        app.is_deleted = 0
                    AND
                        center.is_deleted = 0
                    AND
                        app.company_id = " . $companyId . "
                    AND
                        center.`status` = 0
                    GROUP BY
                        app.id
                    ) D
                ON
                    A.raw_application_id_equipment = D.raw_application_id_center
            LEFT JOIN
                (
                    SELECT
                        app.id AS raw_application_id_product
                        , COUNT(prod_list.application_id) AS product_listing_count
                    FROM
                        application app
                    LEFT JOIN
                        product_listing prod_list ON app.id = prod_list.application_id
                    WHERE
                        app.is_deleted = 0
                    AND
                        prod_list.is_deleted = 0
                    AND
                        app.company_id = " . $companyId . "
                    AND
                        prod_list.`status` = 0
                    GROUP BY
                        app.id
                    ) E
                ON
                    A.raw_application_id_equipment = E.raw_application_id_product
                JOIN
                    application app
                ON
                    app.id = A.raw_application_id_equipment
            WHERE
                app.is_deleted = 0
            AND
                app.company_id = " . $companyId . "
        ) MAIN_T_2
        GROUP BY
            applicant_raw_id,
            company_id
        )

        SELECT
            *
        FROM
            application_detail_count app_count
        LEFT JOIN
            application_detail_comply  app_comply
        ON
            app_count.applicant_raw_id =  app_comply.applicant_raw_id";

        $results = DB::select(DB::raw($query));

        return response()->json($results);
    }

    public function update_status(Request $request)
    {
        $app_status = $request->application_status;
        $status = '';
        if ($app_status == 1) {
            $status = 'For-Review';
        } else if ($app_status == 2) {
            $status = 'For-Validation';
        } else if ($app_status == 3) {
            $status = 'For-Endorsement';
        } else if ($app_status == 4) {
            $status = 'For-Recommendation';
        } else {
            $status = 'Proceeded Inspection';
        }

        try {
            DB::beginTransaction();
            $application = Application::findOrFail($request->id);
            $last_reviewer = $application->reviewer_assigned;
            if ($application != null) {
                $application->update([
                    'application_status' => $request->application_status,
                    'reviewer_assigned' => 2,
                    'last_reviewer_assigned' => 100,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => "Applicant  " . $status . " Successfully",
                    'application' => $application,
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "code" => 400,
                "result" => 'error',
                "message" => "No Records Found",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
