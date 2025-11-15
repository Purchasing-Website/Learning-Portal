<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        
        $users = User::where('role', UserRole::STUDENT->value)->paginate(10);

        $admins = User::where('role', UserRole::ADMIN->value)->paginate(10);

        return view('admins.users.user_view', compact('users','admins'  ) );
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
} 