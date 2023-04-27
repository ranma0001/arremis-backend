<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantAccountInfo;
use App\Models\ApplicantCompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->input('applicant_info'), [

            //APPLICANT VALIDATION
            'applicant_firstname' => 'nullable|string|min:2|max:100',
            'applicant_middlename' => 'nullable|string|min:1|max:100',
            'applicant_lastname' => 'nullable|string|min:1|max:100',
            'applicant_extensionname' => 'nullable|string',
            'designation' => 'nullable|string',

        ]);

        $validator_company = Validator::make($request->input('company_info'), [
            //APPLICANT COMPANY VALIDATION
            'company_name' => 'string|min:2|max:100|nullable',
            'year_establish' => 'nullable|numeric|between:1700,2022|nullable',

            'tel_no' => 'string|nullable',
            'fax_no' => 'string|nullable',
            'email' => 'string|nullable',
            'business_organization_type' => 'string|nullable',

            'region' => 'string|nullable',
            'province' => 'string|nullable',
            'municipality' => 'string|nullable',
            'barangay' => 'string|nullable',
            'address_street' => 'string|nullable',
            'owner_name' => 'string|nullable',
            'map_id' => 'string|nullable',
            'latitude' => 'string|nullable',
            'longitude' => 'string|nullable',
            'marker_description' => 'string|nullable',
            'address_street' => 'string|nullable',
            'application_type' => 'string|nullable',
            'application_date' => 'date|nullable',
        ]);

        $validator_account = Validator::make($request->input('account_info'), [
            'username' => 'required|string',
            'password' => 'required|string',
            'status' => 'required|integer',
            'owner_name' => 'string',
            'profile_picture' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if ($validator_company->fails()) {
            return response()->json($validator_company->errors(), 400);

        }
        if ($validator_account->fails()) {
            return response()->json($validator_account->errors(), 400);
        }

        try {

            \DB::beginTransaction();

            if ($request->has('applicant_info')) {
                $applicant = Applicant::create([
                    'applicant_firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'applicant_middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'applicant_lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'applicant_extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'designation' => $request->input('applicant_info')['designation'],
                ]);
            }

            if ($request->has('account_info')) {
                $applicant_account = ApplicantAccountInfo::create([
                    'applicant_id' => $applicant->id,
                    'username' => $request->input('account_info')['username'],
                    'password' => Hash::make($request->input('account_info')['password'], ),
                    'status' => $request->input('account_info')['status'],
                    'profile_picture' => $request->input('account_info')['profile_picture'],
                ]);
            }

            if ($request->has('company_info')) {
                $applicant_company = ApplicantCompanyInfo::create([
                    'applicant_id' => $applicant->id,
                    'company_name' => $request->input('company_info')['company_name'],
                    'year_establish' => $request->input('company_info')['year_establish'],
                    'tel_no' => $request->input('company_info')['tel_no'],
                    'fax_no' => $request->input('company_info')['fax_no'],
                    'email' => $request->input('company_info')['email'],
                    'business_organization_type' => $request->input('company_info')['business_organization_type'],
                    'owner_name' => $request->input('company_info')['owner_name'],
                    'region' => $request->input('company_info')['region'],
                    'province' => $request->input('company_info')['province'],
                    'municipality' => $request->input('company_info')['municipality'],
                    'barangay' => $request->input('company_info')['barangay'],
                    'address_street' => $request->input('company_info')['address_street'],
                    'map_id' => $request->input('company_info')['map_id'],
                    'latitude' => $request->input('company_info')['latitude'],
                    'longitude' => $request->input('company_info')['longitude'],
                    'marker_description' => $request->input('company_info')['marker_description'],
                    'application_type' => $request->input('company_info')['application_type'],
                    'application_date' => $request->input('company_info')['application_date'],
                ]);
            }

            \DB::commit();
            // if ($request->hasFile('profile_picture')) {
            //     $file = $request->file('profile_picture');
            //     $filename = time() . '_' . $file->getClientOriginalName();
            //     $file->storeAs('public/profile_pictures', $filename);
            //     $accountInfo->profile_picture = $filename;
            // }
            return response()->json([
                'message' => 'Applicant created successfully.',
                'applicant' => $applicant,
                'account' => $applicant_account,
                'account' => $applicant_company,
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([$e]);
        }
    }

    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        // $applicant_account = ApplicantAccountInfo::where('applicant_id', '=', $id);
        // $applicant_company = ApplicantCompanyInfo::where('applicant_id', '=', $id);

        if ($applicant && $applicant_account && $applicant_company) {
            \DB::beginTransaction();
            try
            {
                $applicant->delete();
                // $applicant_account->delete();
                // $applicant_company->delete();
                \DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Applicant Deleted Successfully",
                ], 200);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No Records Found',
            ], 404);

        }

    }
}
