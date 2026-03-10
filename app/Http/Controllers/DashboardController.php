<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = User::where('role', UserRole::STUDENT->value)->count();
        $courseCount = Course::count();
        $classCount = Classes::count();
        $lessonCount = Lesson::count();

        return view('admins.dashboard', compact(
            'studentCount',
            'courseCount',
            'classCount',
            'lessonCount'
        ));
    }
}
