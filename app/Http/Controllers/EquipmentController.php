<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.jwt');
    }

    // public function create_equipment(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         //APPLICANT VALIDATION
    //         'application_id' => 'required|string',
    //         'equipment_name' => 'required|string|min:2|max:100',
    //         'equipment_quantity' => 'required|integer',
    //         'image_string' => 'nullable|string',
    //         'review_comment' => 'nullable|string',
    //         'reviewed_by' => 'nullable|string',
    //         'is_verified' => 'nullable|integer',
    //         'review_level' => 'nullable|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     try {

    //         DB::beginTransaction();
    //         $equipment = Equipment::create([
    //             'application_id' => $request->application_id,
    //             'equipment_name' => $request->equipment_name,
    //             'equipment_quantity' => $request->equipment_quantity,
    //             'review_comment' => $request->review_comment,
    //             'reviewed_by' => $request->reviewed_by,
    //             'is_verified' => $request->is_verified,
    //             'review_level' => $request->review_level,
    //         ]);

    //         DB::commit();
    //         return response()->json([
    //             'message' => 'Equipment created successfully.',
    //             'equipment' => $equipment,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([$e]);
    //     }
    // }

    public function create_equipment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'equipments.*.application_id' => 'required|string',
            'equipments.*.equipment_name' => 'required|string|min:2|max:100',
            'equipments.*.equipment_quantity' => 'required|integer',
            'equipments.*.image_string' => 'nullable|string',
            'equipments.*.review_comment' => 'nullable|string',
            'equipments.*.reviewed_by' => 'nullable|string',
            'equipments.*.is_verified' => 'nullable|integer',
            'equipments.*.review_level' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $equipments = $request->input('equipments');
            $applicationId = 0;
            if (!empty($equipments) && is_array($equipments)) {
                $firstRow = $equipments[0];
                $applicationId = $firstRow['application_id'];

            }

            DB::table('equipment')->where('application_id', $applicationId)->delete();

            $equipmentArray = $request->input('equipments');

            if (!is_array($equipmentArray)) {
                throw new \Exception('Invalid equipment data.');
            }

            $createdEquipment = [];

            foreach ($equipmentArray as $equipmentData) {
                $equipment = Equipment::create([
                    'application_id' => $equipmentData['application_id'],
                    'equipment_name' => $equipmentData['equipment_name'],
                    'equipment_quantity' => $equipmentData['equipment_quantity'],
                    'review_comment' => $equipmentData['review_comment'],
                    'reviewed_by' => $equipmentData['reviewed_by'],
                    'is_verified' => $equipmentData['is_verified'],
                    'review_level' => $equipmentData['review_level'],
                ]);

                $createdEquipment[] = $equipment;
            }

            DB::commit();

            return response()->json([
                'message' => 'Equipment created successfully.',
                'equipment' => $createdEquipment,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit_is_delete(Request $request, int $id)
    {

        $status = $request->is_deleted;
        $status = $status == 0 ? "Restored" : "Deleted";

        try {
            DB::beginTransaction();
            $equipment = Equipment::findOrFail($id);
            if ($equipment != null) {
                $equipment->update([
                    'is_deleted' => $request->is_deleted,
                ]);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Equipment " . $status . "  Successfully",
                ], 200);
            }
        } catch (ModelNotFoundException $e) {

            return response()->json([
                "code" => 404,
                "message" => "No Records Found",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function list_equipment(Request $request)
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
