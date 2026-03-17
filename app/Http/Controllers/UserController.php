<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        
        $users = User::where('role', UserRole::STUDENT->value)->paginate(10);

        $admins = User::where('role', UserRole::ADMIN->value)->paginate(10);
        //dd($users);
        return view('admins.users.student', compact('users','admins'  ) );
    }

    public function store( Request $request)
    {
        // Validate and store user logic here
        $student = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => UserRole::STUDENT->value,
        ]);

        return redirect()->route('user.index')->with('success', 'Student created successfully.');
    }

    public function studentClasses()
    {
        $student = Auth::user();

        // Fetch all enrolled classes (active, completed, dropped)
        $classes = $student->enrolledClasses()->withPivot('status', 'progress', 'enrolled_at')->get();

        return view('students.home_view', compact('classes'));
    }

    public function updateStudentPassword(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:users,id'],
            'password' => [
                'required',
                'string',
                'min:8',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('Password must include at least one uppercase letter.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('Password must include at least one number.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[\W_]/', $value)) {
                        $fail('Password must include at least one special character.');
                    }
                },
            ],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password_confirmation.required' => 'Please confirm the password.',
            'password_confirmation.same' => 'Password confirmation does not match.',
        ]);

        $student = User::where('id', $validated['student_id'])
            ->where('role', UserRole::STUDENT->value)
            ->firstOrFail();

        $student->password = Hash::make($validated['password']);
        $student->save();

        return redirect()->route('user.index')->with('success', 'Student password updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('New password must include at least one uppercase letter.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('New password must include at least one number.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[\W_]/', $value)) {
                        $fail('New password must include at least one special character.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (!$user || !Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()
            ->with('success', 'Password changed successfully. You will be logged out shortly.')
            ->with('logout_after_delay', true);
    }

    public function logoutAfterPasswordChange(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in again.');
    }
}
