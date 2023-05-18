<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Equipment;
use App\Models\Facility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{

    // public function create_application(Request $request)
    // {
    //     try {
    //         $application = Application::query()
    //             ->select(DB::raw('max(application_id) as application_id'))
    //             ->where('is_deleted', 0)
    //             ->first();

    //     } catch (Exception $e) {
    //         return response()->json([$e]);
    //     }
    //     $application_id = $application['application_id'] == null ? 0 : $application['application_id'];

    //     $application = Application::create([
    //         'pto_application_id' => 'H4tD06-' . $application_id,
    //         'applicant_id' => $request->applicant_id,
    //         'application_type' => $request->application_type,
    //     ]);

    //     return response()->json([
    //         'data' => $application,
    //     ],
    //     );

    // }

    public function create_application(Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator != null) {
            return $validator;
        }

        try {

            $application_id = $this->getLastApplicantID();

            $application = Application::create([
                'applicant_id' => $request->applicant_id,
                'application_type' => $request->application_type,
            ]);

            $equipments = $request->input('equipments');
            $facilities = $request->input('facilities');

            DB::table('equipment')->where('application_id', $application->id)->delete();
            DB::table('facility')->where('application_id', $application->id)->delete();

            $equipmentArray = $request->input('equipments');
            $facilityArray = $request->input('facilities');

            if (!is_array($equipmentArray)) {
                throw new \Exception('Invalid equipment data.');
            }
            if (!is_array($facilityArray)) {
                throw new \Exception('Invalid equipment data.');
            }

            $createdEquipment = [];
            $createdFacility = [];

            foreach ($facilityArray as $facilityData) {

                $facility = Facility::create([
                    // 'applicant_id' => $request->input('applicant_id'),
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

            // //APPLICANT VALIDATION
            'facilities.*.facility_name' => 'required|string|min:2|max:100',
            'facilities.*.facility_quantity' => 'required|integer',

            'equipments.*.equipment_name' => 'required|string|min:2|max:100',
            'equipments.*.equipment_quantity' => 'required|integer',

        ]);

        $validator->setAttributeNames([

            'facilities.*.application_id' => 'Application ID',
            'facilities.*.facility_name' => 'facility name',
            'facilities.*.facility_quantity' => 'quantity',
            'facilities.*.status' => 'status',
            'facilities.*.review_comment' => 'comment',
            'facilities.*.reviewed_by' => 'reviewer',
            'facilities.*.is_verified' => 'is_verified',

            'equipments.*.application_id' => 'required|string',
            'equipments.*.equipment_name' => 'required|string|min:2|max:100',
            'equipments.*.equipment_quantity' => 'required|integer',

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
