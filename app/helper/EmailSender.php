<?php
namespace App\helper;

use Illuminate\Support\Facades\Mail;

class EmailSender
{
    public static function sendEmail($recipientEmail, $subject, $newpass)
    {
        $data = $newpass;

        $message2 = view('email-template', compact('data'))->render();

        Mail::html($message2, function ($email) use ($recipientEmail, $subject) {
            $email->from('arrarralcantara@gmail.com', 'ARREMIS ADMIN')
                ->to($recipientEmail)
                ->subject($subject);
        });
    }
}
