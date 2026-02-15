<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Classes;
use App\Models\Tier;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tiers = Tier::orderby('id','asc')->get();
        $courses = Course::withCount('classes')
            ->latest()
            ->take(4)
            ->get();
        $classes = Classes::latest()->take(3)->get();

        return view('students.home', compact('tiers','courses','classes'));;
    
        //return view('students.home', compact('courses','classes'));
    }

    public function course()
    {
        $courses = Course::withCount('classes')
            ->latest()
            ->get();

        return view('students.course', compact('courses'));
    }

    public function class()
    {
        $enrollmentByClass = collect();

        if (Auth::check()) {
            $enrollmentByClass = Enrollment::where('student_id', Auth::id())
                ->get(['class_id', 'progress'])   // add more columns if needed
                ->keyBy('class_id');
        }

        $classes = Classes::latest()->get()->map(function ($row) use ($enrollmentByClass) {
            $enrollment = $enrollmentByClass->get($row->id);

            return [
                'class_id'           => 'CLS-' . str_pad($row->id, 4, '0', STR_PAD_LEFT), // 1 -> CLS-0001
                'class_name'         => $row->title,        // or custom text like "风水入门 · Feng Shui Basics"
                'program_name'       => $row->tier->name,        // static or from another column/table
                'enrolled'           => (bool) $enrollment,  //(bool) $row->is_active,
                'progress'           => $enrollment ? (float) $enrollment->progress : 0, // replace with real calculation
                'duration_total_min' => 320,                // replace with real value
                'time_spent_min'     => 118,                // replace with real value
                'popularity'         => 96,                 // replace with real value
            ];
        });

        //dd($classes);
        return view('students.class', ['classes' => $classes]);

    }

    public function getCourseClass($id)
    {
        $classRows = Classes::with('tier:id,name')
            ->join('class_course', 'class_course.class_id', '=', 'classes.id')
            ->where('class_course.course_id', $id)
            ->orderByDesc('classes.created_at')
            ->get(['classes.id', 'classes.title', 'classes.tier_id']);

        $enrollmentByClass = collect();
        if (Auth::check() && $classRows->isNotEmpty()) {
            $enrollmentByClass = Enrollment::where('student_id', Auth::id())
                ->whereIn('class_id', $classRows->pluck('id'))
                ->get(['class_id', 'progress'])
                ->keyBy('class_id');
        }

        $classes = $classRows->map(function ($row) use ($enrollmentByClass) {
            $enrollment = $enrollmentByClass->get($row->id);

            return [
                'class_id'           => 'CLS-' . str_pad($row->id, 4, '0', STR_PAD_LEFT), // 1 -> CLS-0001
                'classID'            => $row->id,
                'class_name'         => $row->title,        // or custom text like "风水入门 · Feng Shui Basics"
                'program_name'       => $row->tier->name,        // static or from another column/table
                'enrolled'           => (bool) $enrollment,  //(bool) $row->is_active,
                'progress'           => $enrollment ? (float) $enrollment->progress : 0, // replace with real calculation
                'duration_total_min' => 320,                // replace with real value
                'time_spent_min'     => 118,                // replace with real value
                'popularity'         => 96,                 // replace with real value
            ];
        });

        //dd($classes);
        return view('students.class', ['classes' => $classes]);

    }
}
