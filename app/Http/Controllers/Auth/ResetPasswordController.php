<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Reset the given user's password without auto-login.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password): void
    {
        if (! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email before resetting your password.'],
            ]);
        }

        // Use the model's "hashed" cast for consistent password storage.
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect()->route('login')->with('status', 'Password reset successful. Please log in with your new password.');
    }
}
