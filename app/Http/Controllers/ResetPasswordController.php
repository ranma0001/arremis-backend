<?php

namespace App\Http\Controllers;

use App\helper\EmailSender;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {

        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset email sent.']);
        } else {
            return response()->json(['error' => 'Unable to send reset link.'], 400);
        }
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    protected function broker()
    {
        return Password::broker();
    }

    public function changePassword(Request $request)
    {
        $token = $request->input('token');
        $email = $request->input('email');
        $passwordReset = PasswordReset::where('email', $email)->first();

        if (!$passwordReset) {
            return view('token-expired');
        }

        if (Hash::check($token, $passwordReset->token)) {

            $password = Artisan::call('password:generate');
            $output = Artisan::output();
            $generatedPassword = trim(str_replace('Generated Password:', '', $output));
            EmailSender::sendEmail($email, 'New Password Sent', $generatedPassword);

            $user = User::where('email', $email)->first();
            if ($user != null) {
                $user->update([
                    'password' => Hash::make($generatedPassword),
                ]);

                DB::table('password_resets')->where('email', $email)->delete();

                return view('reset-password');
            } else {
                return view('token-expired');
            }

        }
    }

}
