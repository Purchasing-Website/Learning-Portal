<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected bool $blockedForUnverified = false;
    protected ?string $blockedUnverifiedEmail = null;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Handle user after successful login.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        $GetRole = $user->role;
        if ($GetRole === 'superadmin') {
            return redirect('/admin/dashboard');
        } elseif ($GetRole === 'admin') {
            return redirect('/admin/dashboard');
        } else {
            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->put('pending_verification_email', $user->email);

                return redirect()
                    ->route('verification.pending')
                    ->with('status', 'Please verify your email before logging in.');
            }

            return redirect('/');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        $adminHost = config('app.admin_url');

        if (request()->getHost() === $adminHost) {
            return view('auth.admin_login'); // admin UI
        }

        return view('auth.studentlogin'); // normal UI
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($this->blockedForUnverified) {
            $request->session()->put('pending_verification_email', $this->blockedUnverifiedEmail);

            return redirect()
                ->route('verification.pending')
                ->with('status', 'Please verify your email before logging in.');
        }

        if ($user && ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Wrong password. Please try again.'],
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $this->blockedForUnverified = false;
        $this->blockedUnverifiedEmail = null;
        $user = User::where('email', $request->input('email'))->first();

        if ($user && ! in_array($user->role, ['admin', 'superadmin'], true) && ! $user->hasVerifiedEmail()) {
            $this->blockedForUnverified = true;
            $this->blockedUnverifiedEmail = $user->email;
            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

}
