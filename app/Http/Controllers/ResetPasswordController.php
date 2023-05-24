<?php

namespace App\Http\Controllers;

use App\helper\EmailSender;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

    public function getToken(Request $request)
    {
        $token = $request->input('token');
        $email = $request->input('email');
        $passwordReset = PasswordReset::where('email', $email)->first();

        if (!$passwordReset) {
            return 1;
        }

        if (Hash::check($token, $passwordReset->token)) {

            $password = Artisan::call('password:generate');
            $output = Artisan::output();
            $generatedPassword = trim(str_replace('Generated Password:', '', $output));
            EmailSender::sendEmail($email, 'New Password Sent', $generatedPassword);

            //put update password here
            //put delete token here

            return view('reset-password');
        } else {
            // Token is invalid
            return 3;
        }

    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $response = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been updated.'], 200);
        } else {
            return response()->json(['error' => 'Unable to update password.'], 400);
        }
    }

}
