<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantAccountInfo;
use App\Models\ApplicantCompanyInfo;
use Illuminate\Http\Request;
use Validator;

class ApplicantController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicant_id' => 'required|integer',
            'applicant_name' => 'required|string|min:2|max:100',
            'designation' => 'required|string|min:2|max:100',
            'company_name' => 'required|string|min:2|max:100',
            'map_id' => 'required|string|min:2|max:100',
            'latitude' => 'required|string|min:2|max:100',
            'longitude' => 'required|string|min:2|max:100',
            'marker_description' => 'required|string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $applicant = Applicant::create([
            'applicant_name' => $request->applicant_name,
            'designation' => $request->designation,
            'company_name' => $request->company_name,
            'map_id' => $request->map_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'marker_description' => $request->marker_description,
        ]);

        $applicant_account = ApplicantAccountInfo::create([
            'applicant_name' => $request->applicant_name,
            'designation' => $request->designation,
            'company_name' => $request->company_name,
            'map_id' => $request->map_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'marker_description' => $request->marker_description,
        ]);

        $applicant_company = ApplicantCompanyInfo::create([
            'applicant_name' => $request->applicant_name,
            'designation' => $request->designation,
            'company_name' => $request->company_name,
            'map_id' => $request->map_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'marker_description' => $request->marker_description,
        ]);

        return response()->json([
            'message' => 'Applicant inserted !',
            'user' => $applicant,
        ], 201);
    }
}
