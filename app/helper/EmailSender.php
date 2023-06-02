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

    public static function sendNotif($recipientEmail, $subject, $blade_name)
    {
        $message2 = view($blade_name)->render();
        Mail::html($message2, function ($email) use ($recipientEmail, $subject) {
            $email->from('arrarralcantara@gmail.com', 'ARREMIS ADMIN')
                ->to($recipientEmail)
                ->subject($subject);
        });

    }

    public static function sendNotifWithCC($recipientEmail, $ccEmails, $subject, $blade_name, $data)
    {
        $message2 = view($blade_name, compact('data'))->render();
        Mail::send([], [], function ($email) use ($recipientEmail, $subject, $ccEmails, $message2) {
            $email->from('arrarralcantara@gmail.com', 'ARREMIS ADMIN')
                ->to($recipientEmail)
                ->cc($ccEmails)
                ->subject($subject)
                ->setBody($message2, 'text/html');
        });
    }

}
