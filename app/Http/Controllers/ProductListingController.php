<?php

namespace App\Http\Controllers;

use App\Models\ProductListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductListingController extends Controller
{

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
