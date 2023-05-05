<?php

namespace App\Http\Controllers;

use App\Models\ServiceCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceCenterController extends Controller
{
    public function create_service_center(Request $request)
    {

        $validator = Validator::make($request->all(), [

            //APPLICANT VALIDATION
            'applicant_id' => 'required|integer',
            'center_name' => 'required|string|min:2|max:100',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {

            \DB::beginTransaction();
            $service_center = ServiceCenter::create([
                // 'applicant_id' => $request->input('applicant_id'),
                'applicant_id' => $request->applicant_id,
                'center_name' => $request->center_name,
                'contact' => $request->contact,
                'email' => $request->email,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'address' => $request->address,
                'review_comment' => $request->review_comment,
                'reviewed_by' => $request->reviewed_by,
                'is_verified' => $request->is_verified,
                'review_level' => $request->review_level,
            ]);

            \DB::commit();
            return response()->json([
                'message' => 'Service Center created successfully.',
                'Service Center' => $service_center,
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
            $service_center = ServiceCenter::find($id);

            $service_center->update([
                'is_deleted' => $request->is_deleted,
            ]);
            \DB::commit();

            return response()->json([
                'status' => 200,
                'message' => "Service Center Deleted Successfully",
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e], 404);
        }
    }

    public function update_service_center(Request $request, int $id)
    {
        try {
            \DB::beginTransaction();
            $service_center = ServiceCenter::find($id);

            $service_center->update([
                'applicant_id' => $request->applicant_id,
                'center_name' => $request->center_name,
                'contact' => $request->contact,
                'email' => $request->email,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'address' => $request->address,
                'review_comment' => $request->review_comment,
                'reviewed_by' => $request->is_delreviewed_byeted,
                'is_verified' => $request->is_verified,
                'review_level' => $request->review_level,
            ]);
            \DB::commit();

            return response()->json([
                'status' => 200,
                'message' => "Service Center Updated Successfully",
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e], 404);
        }
    }

}
