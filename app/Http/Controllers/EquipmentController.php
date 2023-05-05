<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
    public function create_equipment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            //APPLICANT VALIDATION
            'applicant_id' => 'required|integer',
            'equipment_name' => 'required|string|min:2|max:100',
            'equipment_quantity' => 'required|integer',
            'image_string' => 'nullable|string',
            'review_comment' => 'nullable|string',
            'reviewed_by' => 'nullable|string',
            'is_verified' => 'nullable|integer',
            'review_level' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            \DB::beginTransaction();
            $equipment = Equipment::create([
                'applicant_id' => $request->applicant_id,
                'equipment_name' => $request->equipment_name,
                'equipment_quantity' => $request->equipment_quantity,
                'review_comment' => $request->review_comment,
                'reviewed_by' => $request->reviewed_by,
                'is_verified' => $request->is_verified,
                'review_level' => $request->review_level,
            ]);

            \DB::commit();
            return response()->json([
                'message' => 'Equipment created successfully.',
                'equipment' => $equipment,
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
            $equipment = Equipment::find($id);

            $equipment->update([
                'is_deleted' => $request->is_deleted,
            ]);

            \DB::commit();
            return response()->json([
                'status' => 200,
                'message' => "Equipment Deleted Successfully",
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e]);
        }
    }

    public function view_facility(Request $request)
    {
        $query = Equipment::select('*');

        //filtering
        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);

        return response()->json($response);
    }

}
