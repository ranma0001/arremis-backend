<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.jwt');
    }

    public function create_facility(Request $request)
    {

        $validator = Validator::make($request->all(), [

            //APPLICANT VALIDATION
            'facilities.*.application_id' => 'required|integer',
            'facilities.*.facility_name' => 'required|string|min:2|max:100',
            'facilities.*.facility_quantity' => 'required|integer',
            'facilities.*.status' => 'required|integer',
            //'facilities.*.image_string' => 'nullable|string',
            'facilities.*.review_comment' => 'nullable|string',
            'facilities.*.reviewed_by' => 'nullable|string',
            'facilities.*.is_verified' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            DB::beginTransaction();

            $facilities = $request->input('facilities');
            $applicationId = 0;
            if (!empty($facilities) && is_array($facilities)) {
                $firstRow = $facilities[0];
                $applicationId = $firstRow['application_id'];

            }

            DB::table('facility')->where('application_id', $applicationId)->delete();

            $facilityArray = $request->input('facilities');

            if (!is_array($facilityArray)) {
                throw new \Exception('Invalid facility data.');
            }

            $createdFacility = [];
            foreach ($facilityArray as $facilityData) {

                $facility = Facility::create([
                    // 'applicant_id' => $request->input('applicant_id'),
                    'application_id' => $facilityData['application_id'],
                    'facility_name' => $facilityData['facility_name'],
                    'facility_quantity' => $facilityData['facility_quantity'],
                    'image_string' => $facilityData['image_string'],
                    'review_comment' => $facilityData['review_comment'],
                    'reviewed_by' => $facilityData['reviewed_by'],
                    'is_verified' => $facilityData['is_verified'],
                    'review_level' => $facilityData['review_level'],
                    'status' => $facilityData['status'],
                ]);

                $createdFacility[] = $facility;
            }
            DB::commit();
            return response()->json([
                'message' => 'Facilities created successfully.',
                'facility' => $createdFacility,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([$e]);
        }
    }

    public function edit_is_delete(Request $request, int $id)
    {

        $status = $request->is_deleted;
        $status = $status == 0 ? "Restored" : "Deleted";

        try {
            DB::beginTransaction();
            $facility = Facility::findOrFail($id);
            if ($facility != null) {
                $facility->update([
                    'is_deleted' => $request->is_deleted,
                ]);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Facility " . $status . " Successfully",
                ], 200);
            }
        } catch (ModelNotFoundException $e) {

            return response()->json([
                "code" => 404,
                "message" => "No Records Found",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

    public function show(Request $request, $id)
    {
        $facility = Facility::query()
            ->select('application_id', 'application_id', 'facility_name', 'facility_quantity',
                DB::raw('fn_facility_status(status) as status'), 'review_comment',
                'reviewed_by', 'review_level', 'is_deleted')
            ->where('id', $id)
            ->first();

        if ($facility != null) {
            return response()->json(['facility' => $facility]);

        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No Records Found',
            ], 404);

        }

    }

    public function list_facility(Request $request)
    {
        $query = Facility::select('*');

        //filtering
        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);

        return response()->json($response);
    }

}
