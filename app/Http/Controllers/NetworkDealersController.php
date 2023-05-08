<?php

namespace App\Http\Controllers;

use App\Models\NetworkDealers;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NetworkDealersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create_network_dealers(Request $request)
    {

        $validator = Validator::make($request->all(), [

            //APPLICANT VALIDATION
            'applicant_id' => 'required|integer',
            'company_name' => 'required|string|min:2|max:100',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
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

    public function edit_is_delete(Request $request, int $id)
    {

        $status = $request->is_deleted;
        $status = $status == 0 ? "Restored" : "Deleted";

        try {
            \DB::beginTransaction();
            $network_dealer = NetworkDealers::findOrFail($id);

            if ($network_dealer != null) {
                $network_dealer->update([
                    'is_deleted' => $request->is_deleted,
                ]);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Network Dealer " . $status . " Successfully",
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "code" => 400,
                "result" => 'error',
                "message" => "No Records Found",
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e]);
        }
    }

    public function list_network_dealer(Request $request)
    {
        $query = NetworkDealers::select('*');

        //filtering
        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);

        return response()->json($response);
    }
}
