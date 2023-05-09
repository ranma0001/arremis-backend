<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductListingController extends Controller
{

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
            'exporter' => 'string|min:2|max:100',
            'cc_no' => 'string|min:2|max:100',
            'country_manufacturer' => 'string|min:2|max:100',
            'assembler' => 'string|min:2|max:100',
            'inspected' => 'nullable|string',
            'image_string' => 'nullable|string',
            'review_comment' => 'nullable|string',
            'reviewed_by' => 'nullable|string',
            'is_verified' => 'nullable|integer',
            'review_level' => 'nullable|integer',
        ]);
    }

}
