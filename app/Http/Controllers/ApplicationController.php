<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantAccountInfo;
use App\Models\ApplicantCompanyInfo;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function create_applicant(Request $request)
    {

        $validator = Validator::make($request->input('applicant_info'), [

            //APPLICANT VALIDATION
            'applicant_firstname' => 'nullable|string|min:2|max:100',
            'applicant_middlename' => 'nullable|string|min:1|max:100',
            'applicant_lastname' => 'nullable|string|min:1|max:100',
            'applicant_extensionname' => 'nullable|string',
            'designation' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $validator_company = Validator::make($request->input('company_info'), [
            //APPLICANT COMPANY VALIDATION
            'company_name' => 'string|min:2|max:100|nullable',
            'year_establish' => 'nullable|numeric|between:1700,2022|nullable',
            'tel_no' => 'string|nullable',
            'fax_no' => 'string|nullable',
            'company_email' => 'string|nullable',
            'business_organization_type' => 'numeric|nullable',
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
            'application_type' => 'integer|nullable',
            'application_date' => 'date|nullable',
        ]);

        $validator_account = Validator::make($request->input('user_info'), [
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'min:6|required_with:password|same:password',
            'user_type' => 'required|integer',
            'status' => 'required|integer',
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

            if ($request->has('user_info')) {
                $user = User::create([
                    'firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'email' => $request->input('user_info')['email'],
                    'password' => Hash::make($request->input('user_info')['password']),
                    'user_type' => $request->input('user_info')['user_type'],
                    'status' => $request->input('user_info')['status'],
                ]);
            }

            if ($request->has('applicant_info')) {
                $applicant = Applicant::create([
                    'user_id' => $user->id,
                    'applicant_firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'applicant_middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'applicant_lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'applicant_extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'designation' => $request->input('applicant_info')['designation'],
                ]);
            }

            if ($request->has('company_info')) {
                $applicant_company = ApplicantCompanyInfo::create([
                    'applicant_id' => $applicant->id,
                    'company_name' => $request->input('company_info')['company_name'],
                    'year_establish' => $request->input('company_info')['year_establish'],
                    'tel_no' => $request->input('company_info')['tel_no'],
                    'fax_no' => $request->input('company_info')['fax_no'],
                    'company_email' => $request->input('company_info')['company_email'],
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

            DB::commit();
            return response()->json([
                'message' => 'Applicant created successfully.',
                'applicant' => $applicant,
                'user' => $user,
                'company info' => $applicant_company,
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([$e]);
        }
    }

    public function update_applicant(Request $request, int $id)
    {

        $validator = Validator::make($request->input('applicant_info'), [

            //APPLICANT VALIDATION
            'applicant_firstname' => 'nullable|string|min:2|max:100',
            'applicant_middlename' => 'nullable|string|min:1|max:100',
            'applicant_lastname' => 'nullable|string|min:1|max:100',
            'applicant_extensionname' => 'nullable|string',
            'designation' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $validator_company = Validator::make($request->input('company_info'), [
            //APPLICANT COMPANY VALIDATION
            'company_name' => 'string|min:2|max:100|nullable',
            'year_establish' => 'nullable|numeric|between:1700,2022|nullable',
            'tel_no' => 'string|nullable',
            'fax_no' => 'string|nullable',
            'company_email' => 'string|nullable',
            'business_organization_type' => 'numeric|nullable',
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
            'application_type' => 'integer|nullable',
            'application_date' => 'date|nullable',
        ]);

        $validator_account = Validator::make($request->input('user_info'), [
            'password' => 'required|string|min:6',
            'password_confirmation' => 'min:6|required_with:password|same:password',
            'user_type' => 'required|integer',
            'status' => 'required|integer',
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
            $applicant = Applicant::find($id);
            $user = User::find($applicant['user_id']);
            $applicant_company = ApplicantCompanyInfo::find($id);

            // return response()->json($applicant['user_id']);

            if ($request->has('applicant_info')) {
                $applicant->update([
                    'applicant_firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'applicant_middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'applicant_lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'applicant_extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'designation' => $request->input('applicant_info')['designation'],
                    'profile_picture' => $request->input('applicant_info')['profile_picture'],
                ]);
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'No Applicant found',
                ], 404);
            }

            if ($request->has('user_info')) {
                $user->update([
                    'firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'email' => $request->input('user_info')['email'],
                    'password' => Hash::make($request->input('user_info')['password']),
                    'user_type' => $request->input('user_info')['user_type'],
                    'status' => $request->input('user_info')['status'],
                ]);

            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'No Applicant2 found',
                ], 404);
            }

            if ($request->has('company_info')) {
                $applicant_company->update([
                    'company_name' => $request->input('company_info')['company_name'],
                    'year_establish' => $request->input('company_info')['year_establish'],
                    'tel_no' => $request->input('company_info')['tel_no'],
                    'fax_no' => $request->input('company_info')['fax_no'],
                    'company_email' => $request->input('company_info')['company_email'],
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
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'No Applicant2 found',
                ], 404);
            }

            \DB::commit();
            return $this->asJson([
                "User Information" => $user,
                "Applicant Information" => $applicant,
                "Company Information" => $applicant_company,
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e]);
        }
    }

    public function edit_is_delete(Request $request, int $id)
    {
        try {
            \DB::beginTransaction();
            $applicant = Applicant::find($id);
            if ($request->has('applicant_info')) {
                $applicant->update([
                    'is_deleted' => $request->input('applicant_info')['is_deleted'],
                ]);

                \DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Applicant Deleted Successfully",
                ], 200);
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'No Applicant found',
                ], 404);
            }
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

    public function show(Request $request, $id)
    {
        $first_name = $request->input('firstName');
        // $userName = $request->input('userName');

        $applicant = Applicant::query()
            ->select('applicant_firstname', 'applicant_middlename', 'applicant_lastname', 'applicant_extensionname',
                'designation')
            ->where('id', $id)
            ->where('is_deleted', 1)
            ->where(function ($query) use ($first_name) {
                if ($first_name !== null) {
                    $query->where('applicant_firstname', 'like', '%' . $first_name . '%');
                }
            })
            ->first();

        $applicant_account = ApplicantAccountInfo::query()
            ->select('applicant_id', 'username', 'status',
                'profile_picture')
            ->where('id', $id)
            ->first();

        if ($applicant_account != null or $applicant != null) {
            return response()->json([
                'applicant' => $applicant,
                'applicant_account' => $applicant_account,
            ]);

        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No Records Found',
            ], 404);

        }

    }
}
