<?php

namespace App\Http\Controllers;

use App\Models\DocumentaryRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentaryRequirementController extends Controller
{
    public function create_document_requirement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|integer',
            'document_name' => 'required|string|min:2|max:100',
            'facility_quantity' => 'required|integer',
            'status' => 'required|integer',
            'review_comment' => 'integer',
            'reviewed_by' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            DB::beginTransaction();

            $document_requirement = DocumentaryRequirement::create([
                'application_id' => $request->application_id,
                'document_name' => $request->document_name,
                'file_name' => $request->file_name,
                'file_type' => $request->file_type,
                'file_location' => $request->file_location,
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Facilities created successfully.',
                'facility' => $document_requirement,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([$e]);
        }

    }
}
