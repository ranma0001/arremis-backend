<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class JWTController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
            'user_type' => 'required|integer',
            'status' => 'required|integer',
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
            'status' => $request->status,
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

        return $this->respondWithToken($token, 1);
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
        if ($ret_type == 1) {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'user' => auth()->user(),
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        } else {
            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        }
    }
}
