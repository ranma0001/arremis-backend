<?php

namespace App\Http\Controllers;

use App\Models\NetworkDealers;
use Illuminate\Http\Request;

class NetworkDealersController extends Controller
{
    public function create_network_dealers(Request $request)
    {

        // $validator = Validator::make($request->all(), [

        //     //APPLICANT VALIDATION
        //     'applicant_id' => 'required|integer',
        //     'facility_name' => 'required|string|min:2|max:100',
        //     'facility_quantity' => 'required|integer',
        //     'status' => 'required|integer',
        //     'image_string' => 'nullable|string',
        //     'review_comment' => 'nullable|string',
        //     'reviewed_by' => 'nullable|string',
        //     'is_verified' => 'nullable|integer',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }

        return 1;
        try {

            \DB::beginTransaction();
            $network_dealer = NetworkDealers::create([
                // 'applicant_id' => $request->input('applicant_id'),
                'applicant_id' => $request->applicant_id,
                'company_name' => $request->company_name,
                'contact' => $request->contact,
                'address' => $request->address,
                'review_comment' => $request->review_comment,
                'reviewed_by' => $request->reviewed_by,
                'is_verified' => $request->is_verified,
                'review_level' => $request->review_level,
            ]);

            \DB::commit();
            return response()->json([
                'message' => 'Network Dealer created successfully.',
                'Network Dealers' => $network_dealer,
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([$e]);
        }
    }
}
