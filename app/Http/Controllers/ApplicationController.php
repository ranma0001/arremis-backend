<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantCompanyInfo;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['create_applicant']]);
    }

    public function create_applicant(Request $request)
    {
        $validator = $this->validateInput($request, 0);

        if ($validator != null) {
            return $validator;
        }

        $applicantInfo = $request->input('applicant_info', []);
        $companyInfo = $request->input('company_info', []);
        $userInfo = $request->input('user_info', []);

        try {

            \DB::beginTransaction();

            if ($userInfo) {
                $user = User::create([
                    'firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'email' => $request->input('user_info')['email'],
                    'password' => Hash::make($request->input('user_info')['password']),
                    'user_type' => 1,
                    'status' => 1,
                ]);
            }

            if ($applicantInfo) {
                $applicant = Applicant::create([
                    'user_id' => $user->id,
                    'applicant_firstname' => $request->input('applicant_info')['applicant_firstname'],
                    'applicant_middlename' => $request->input('applicant_info')['applicant_middlename'],
                    'applicant_lastname' => $request->input('applicant_info')['applicant_lastname'],
                    'applicant_extensionname' => $request->input('applicant_info')['applicant_extensionname'],
                    'designation' => $request->input('applicant_info')['designation'],
                ]);
            }
            if ($companyInfo) {
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
                    'application_type' => $request->input('company_info')['application_type'],
                    'application_date' => $request->input('company_info')['application_date'],
                ]);
            }

            DB::commit();
            return response()->json([
                'data' => [
                    'applicant' => $applicant,
                    'user' => $user,
                    'company info' => $applicant_company,
                ],
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([$e]);
        }
    }

    public function update_applicant(Request $request, int $id)
    {
        $validator = $this->validateInput($request, 0);

        if ($validator) {
            return $validator;
        }

        $applicant = Applicant::findOrFail($id);
        $user = User::findOrFail($applicant->user_id);
        $applicant_company = ApplicantCompanyInfo::findOrFail($id);

        $applicant_data = $request->input('applicant_info', []);
        $user_data = $request->input('user_info', []);
        $company_data = $request->input('company_info', []);

        if (empty($applicant_data) || empty($user_data) || empty($company_data)) {
            return response()->json([
                'code' => 400,
                'result' => 'error',
                'message' => 'Required input field is missing.',
            ]);
        }

        try {
            \DB::beginTransaction();

            $applicant->update([
                'applicant_firstname' => $applicant_data['applicant_firstname'],
                'applicant_middlename' => $applicant_data['applicant_middlename'],
                'applicant_lastname' => $applicant_data['applicant_lastname'],
                'applicant_extensionname' => $applicant_data['applicant_extensionname'],
                'designation' => $applicant_data['designation'],
                'profile_picture' => $applicant_data['profile_picture'],
            ]);

            $user->update([
                'firstname' => $applicant_data['applicant_firstname'],
                'middlename' => $applicant_data['applicant_middlename'],
                'lastname' => $applicant_data['applicant_lastname'],
                'extensionname' => $applicant_data['applicant_extensionname'],
                'email' => $user_data['email'],
                'password' => Hash::make($user_data['password']),
                'user_type' => $user_data['user_type'],
                'status' => $user_data['status'],
            ]);

            $applicant_company->update([
                'company_name' => $company_data['company_name'],
                'year_establish' => $company_data['year_establish'],
                'tel_no' => $company_data['tel_no'],
                'fax_no' => $company_data['fax_no'],
                'company_email' => $company_data['company_email'],
                'business_organization_type' => $company_data['business_organization_type'],
                'owner_name' => $company_data['owner_name'],
                'region' => $company_data['region'],
                'province' => $company_data['province'],
                'municipality' => $company_data['municipality'],
                'barangay' => $company_data['barangay'],
                'address_street' => $company_data['address_street'],
                'map_id' => $company_data['map_id'],
                'latitude' => $company_data['latitude'],
                'longitude' => $company_data['longitude'],
                'marker_description' => $company_data['marker_description'],
                'application_type' => $company_data['application_type'],
                'application_date' => $company_data['application_date'],
            ]);

            \DB::commit();

            return response()->json([
                'data' => [
                    'applicant' => $applicant,
                    'user' => $user,
                    'company_info' => $applicant_company,
                ],
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'No Records Found',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([$e]);
        }
    }

    //Do not use
    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        $applicant_account = ApplicantAccountInfo::where('applicant_id', '=', $id);
        $applicant_company = ApplicantCompanyInfo::where('applicant_id', '=', $id);

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

            return $this->asjson([
                "code" => 400,
                "result" => 'error',
                "message" => "No Records Found",
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        $applicant = Applicant::query()
            ->select('applicant_firstname', 'applicant_middlename', 'applicant_lastname', 'applicant_extensionname',
                'designation')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->when($request->filled('firstName'), function ($query) use ($request) {
                $query->where('applicant_firstname', 'like', '%' . $request->input('firstName') . '%');
            })
            ->first();

        $applicant_account = User::query()
            ->select('firstname', 'middlename', 'lastname', 'email', 'status', 'user_type')
            ->where('id', $id)
            ->first();

        $applicant_company = ApplicantCompanyInfo::query()
            ->select('company_name', 'year_establish', 'tel_no', 'fax_no', 'company_email', 'business_organization_type')
            ->where('applicant_id', $id)
            ->first();

        if ($applicant_account && $applicant) {
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

    public function list_applicant(Request $request)
    {
        $query = Applicant::query()
            ->select('applicant.*', 'applicant_company_information.*')
            ->join('applicant_company_information', 'applicant.id', '=', 'applicant_company_information.applicant_id')
            ->join('users', 'users.id', '=', 'applicant.user_id');

        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);
        return response()->json([
            'applicant' => $response,

        ]);

    }

    private function validateInput(Request $request, $isCreate)
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

        //if 1 = Create applicant else update applicant
        if ($isCreate == 1) {

            $validator_account = Validator::make($request->input('user_info'), [
                'password' => 'required|string|min:6',
                'password_confirmation' => 'min:6|required_with:password|same:password',
                'user_type' => 'required|integer',
                'status' => 'required|integer',
            ]);
        } else {
            $validator_account = Validator::make($request->input('user_info'), [
                'password' => 'required|string|min:6',
                'password_confirmation' => 'min:6|required_with:password|same:password',
            ]);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if ($validator_company->fails()) {
            return response()->json($validator_company->errors(), 400);

        }
        if ($validator_account->fails()) {
            return response()->json($validator_account->errors(), 400);
        }
    }

}
