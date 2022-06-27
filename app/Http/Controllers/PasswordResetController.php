<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{

    public function send_reset_password_email(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
        ]);

        $email = $r->email;

        // Check user's email exists or not
        $user = User::where('email', '=', $r->email)->first();

        if ($user !== null) {

            // Generate Token
            $token = Str::random(60);

            // Saving data to the password reset table
            PasswordReset::create([
                'email' => $r->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // Sending Email with pasword reset view
            Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
                $message->subject('Reset your password');
                $message->to($email);
            });

            return response()->json([
                'msg' => 'Password reset email sent successfully, check your email',
                'status' => 'success',
            ], 401);
        } else {
            return response()->json([
                'msg' => 'Provided credentials are incorrect. Please try again',
                'status' => 'failed',
            ], 401);
        }
    }

    public function reset(Request $r)
    {
    }
}
