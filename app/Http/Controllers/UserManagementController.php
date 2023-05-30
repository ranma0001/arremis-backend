<?php

namespace App\Http\Controllers;

use App\helper\EmailSender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function accountApproval(Request $request)
    {
        $statusType = $request->status;

        $status = '';
        if ($statusType == 1) {
            $status = 'For Approval';
        } else if ($statusType == 2) {
            $status = 'Approved';
        } else {
            $status = 'Freeze';
        }

        try {
            DB::beginTransaction();
            $account = User::findOrFail($request->id);

            if ($account) {
                $account->update([
                    'status' => $request->status,
                ]);

                DB::commit();

                if ($statusType == 2) {
                    EmailSender::sendNotif($account->email, 'Account Approved', 'email-account-approved');
                }

                return response()->json([
                    'status' => 200,
                    'message' => "Account is $status",
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "code" => 400,
                "result" => 'error',
                "message" => "No Records Found",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

}
