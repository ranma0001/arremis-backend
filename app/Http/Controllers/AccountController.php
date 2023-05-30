<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function updateAccount(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|min:2|max:100',
            'middlename' => 'string',
            'lastname' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::findOrFail($request->id);

        try {
            DB::beginTransaction();
            $user->update([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'extensionname' => $request->extensionname,
                'email' => $request->email,
                'profile_picture' => $request->profile_picture,
            ]);
            DB::commit();

            return response()->json([
                'data' => [
                    'account' => $user,
                ],
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'No Records Found',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

}
