<?php

namespace App\Http\Controllers;

use App\Models\ProductListing;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductListingController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.jwt');
    }

    public function create_product_listing(Request $request)
    {

        $validator = $this->validateInput($request);

        if ($validator != null) {
            return $validator;
        }

        try {
            \DB::beginTransaction();
            $product_listing = ProductListing::create($request->all());

            \DB::commit();
            return response()->json([
                'data' => [
                    'Product Listing' => $product_listing,
                ],
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
            \DB::beginTransaction();
            $product_listing = ProductListing::findOrFail($id);

            if ($product_listing != null) {
                $product_listing->update([
                    'is_deleted' => $request->is_deleted,
                ]);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Product Listing  " . $status . " Successfully",
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

    public function list_product_listing(Request $request)
    {
        $query = ProductListing::select('*');

        //filtering
        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);

        return response()->json($response);
    }

    private function validateInput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //APPLICANT VALIDATION
            'item_name' => 'string|min:2|max:100',
            'item_brand' => 'string|min:2|max:100',
            'description' => 'string|min:2|max:100',
            'manufacturer' => 'string|min:2|max:100',
            'fabricator' => 'string|min:2|max:100',
            'assembler' => 'string|min:2|max:100',
            'distributor' => 'string|min:2|max:100',
            'dealer' => 'string|min:2|max:100',
            'importer' => 'string|min:2|max:100',
            'exporter' => 'string|min:2|max:100',
            'cc_no' => 'string|min:2|max:100',
            'country_manufacturer' => 'string|min:2|max:100',
        ]);
    }

}
