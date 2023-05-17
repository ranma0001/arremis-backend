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
        $this->middleware('custom.jwt');
    }

    public function create_network_dealers(Request $request)
    {

        $validator = Validator::make($request->all(), [

            //APPLICANT VALIDATION
            'application_id' => 'integer',
            'company_name' => 'string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {

            DB::beginTransaction();

            $old_network_dealers = $request->input('network_dealers');
            $applicationId = 0;
            if (!empty($old_network_dealers) && is_array($old_network_dealers)) {
                $firstRow = $old_network_dealers[0];
                $applicationId = $firstRow['application_id'];

            }

            DB::table('network_dealer')->where('application_id', $applicationId)->delete();

            $networkDealerArray = $request->input('network_dealers');

            if (!is_array($networkDealerArray)) {
                throw new \Exception('Invalid network dealer data.');
            }

            $createdNetworkDealerArray = [];

            foreach ($networkDealerArray as $networkDealerData) {
                $network_dealer = NetworkDealers::create([
                    // 'applicant_id' => $request->input('applicant_id'),
                    'application_id' => $networkDealerData['application_id'],
                    'company_name' => $networkDealerData['company_name'],
                    'contact' => $networkDealerData['contact'],
                    'address' => $networkDealerData['address'],
                    'review_comment' => $networkDealerData['review_comment'],
                    'reviewed_by' => $networkDealerData['reviewed_by'],
                    'is_verified' => $networkDealerData['is_verified'],
                    'review_level' => $networkDealerData['review_level'],
                ]);
                $createdNetworkDealer[] = $network_dealer;

            }
            DB::commit();
            return response()->json([
                'message' => 'Network Dealer created successfully.',
                'Network Dealers' => $createdNetworkDealer,
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
            DB::rollBack();
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
