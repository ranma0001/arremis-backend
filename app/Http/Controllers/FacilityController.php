<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    public function create_facility(Request $request)
    {

        $validator = Validator::make($request->all(), [

            //APPLICANT VALIDATION
            'applicant_id' => 'required|integer',
            'facility_name' => 'required|string|min:2|max:100',
            'facility_quantity' => 'required|integer',
            'status' => 'required|integer',
            'image_string' => 'nullable|string',
            'review_comment' => 'nullable|string',
            'reviewed_by' => 'nullable|string',
            'is_verified' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            \DB::beginTransaction();
            $facility = Facility::create([
                // 'applicant_id' => $request->input('applicant_id'),
                'applicant_id' => $request->applicant_id,
                'facility_name' => $request->facility_name,
                'facility_quantity' => $request->facility_quantity,
                'status' => $request->status,
                'image_string' => $request->image_string,
                'review_comment' => $request->review_comment,
                'reviewed_by' => $request->reviewed_by,
                'is_verified' => $request->is_verified,
                'review_level' => $request->review_level,
            ]);

            \DB::commit();
            return response()->json([
                'message' => 'Facility created successfully.',
                'facility' => $facility,
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([$e]);
        }
    }

    public function edit_is_delete(Request $request, int $id)
    {
        try {
            \DB::beginTransaction();
            $facility = Facility::find($id);
            if ($request->has('facility_info')) {
                $facility->update([
                    'is_deleted' => $request->input('facility_info')['is_deleted'],
                ]);

                \DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Facility Deleted Successfully",
                ], 200);
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'No Facility found',
                ], 404);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e]);
        }
    }

    public function show(Request $request, $id)
    {
        $facilityName = $request->input('facilityName');

        $facility = Facility::query()
            ->select('applicant_id', 'applicant_id', 'facility_name', 'facility_quantity',
                DB::raw('fn_facility_status(status) as status'), 'image_string', 'review_comment',
                'reviewed_by', 'is_verified', 'review_level', 'is_deleted')
            ->where('id', $id)
            ->where(function ($query) use ($facilityName) {
                if ($facilityName !== null) {
                    $query->where('facility_name', 'like', '%' . $facilityName . '%');
                }
            })
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

    public function view_facility(Request $request)
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
