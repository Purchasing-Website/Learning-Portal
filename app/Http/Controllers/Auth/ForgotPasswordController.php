<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = User::where('email', $request->email)->first();

        // Generic message to avoid user/email enumeration for unknown emails
        $genericMessage = 'If this email is registered, instructions have been sent.';

        if (! $user) {
            return back()->with('status', $genericMessage);
        }

        if (! $user->hasVerifiedEmail()) {
            // Send verification email first
            $user->sendEmailVerificationNotification();

            return view('verification_link_sent', [
                'status' => 'Your email is not verified yet. We sent a verification email. Please verify your email first, then request password reset again.',
                'mode' => 'email_verification',
                'email' => $user->email,
            ]);
        }

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        // Still keep generic response either way
        if ($response === Password::RESET_LINK_SENT) {
            return view('verification_link_sent', [
                'status' => 'Password reset link has been sent to your email.',
                'mode' => 'password_reset',
                'email' => $request->email,
            ]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Unable to send reset password email right now. Please try again later.',
            ]);
    }
}
