<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantCompanyInfo;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class JWTController extends Controller
{

    public function __construct()
    {
        $this->middleware('custom.jwt', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|min:2|max:100',
            'middlename' => 'string',
            'lastname' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'min:6|required_with:password|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'extensionname' => $request->extensionname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                "error" => 'login_failed',
                "code" => 401,
                'message' => 'The email or password you entered is incorrect. Please try again.',
            ], 401);
        }

        return $this->respondWithToken($token, auth()->user()->user_type);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out.']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 0);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    // 1 with user return else 0
    protected function respondWithToken($token, $ret_type)
    {

        // USER TYPE
        // 0 = Super Admin
        // 1 = Applicant
        // 2 = SRED Super Admin

        if ($ret_type == 0) {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);

        } else if ($ret_type == 1) {
            $applicant = Applicant::query()
                ->select('*')
                ->where('user_id', auth()->user()->id)
                ->where('is_deleted', 0)
                ->first();

            $applicant_company = ApplicantCompanyInfo::query()
                ->select(DB::raw('id as company_id'), 'company_name', 'year_establish', 'tel_no', 'fax_no', 'company_email', 'business_organization_type')
                ->where('applicant_id', $applicant->id)
                ->first();

            $user = auth()->user();
            $token1 = JWTAuth::customClaims([
                'company_id' => $applicant_company->company_id,
                'applicant_id' => $applicant->id,
            ])->fromUser($user);

            return response()->json([
                'token' => $token1,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
                'info' => $applicant,
                'company' => $applicant_company,
            ]);
        } else {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'user' => auth()->user(),
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        }
    }
}
