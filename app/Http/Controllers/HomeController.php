<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Classes;
use App\Models\Tier;

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
        $courses = Course::latest()->take(3)->get();
        $classes = Classes::latest()->take(3)->get();

        
        $academy= Tier::query()
            ->where('is_active', 1)
            ->latest('created_at')
            ->get()
            ->map(function ($tier) {
                $courses = $tier->courses()
                   //->where('is_active', 1)
                    ->latest('created_at')
                    ->take(3) // latest 3 courses per tier
                    ->get()
                    ->map(function ($course) {
                        $classes = $course->classes()
                            //->where('is_active', 1)
                            ->latest('created_at')
                            ->take(3)
                            ->get()
                            ->map(function ($class) {
                                return [
                                    'class_id'   => 'CLS-' . str_pad($class->id, 4, '0', STR_PAD_LEFT),
                                    'class_name' => $class->title,
                                    'total_min'  => (int) ($class->total_min ?? 0),
                                ];
                            })
                            ->values();

                        return [
                            'course_id'   => 'C' . str_pad($course->id, 3, '0', STR_PAD_LEFT),
                            'course_name' => $course->title,
                            'total_min'   => (int) ($course->total_min ?? 0),
                            'classes'     => $classes,
                        ];
                    })
                    ->values();

                return [
                    'tier_id'   => 'T' . str_pad($tier->id, 3, '0', STR_PAD_LEFT),
                    'tier_name' => $tier->name,          // or $tier->name
                    'tier_desc' => $tier->description,
                    'courses'   => $courses,
                ];
            })
            ->values();

            //dd($academy);

        return view('students.home', compact('academy'));;
    
        //return view('students.home', compact('courses','classes'));
    }

    public function course()
    {
        $courses = Course::latest()->get();

        return view('students.course', compact('courses'));
    }

    public function class()
    {
        //$classes = Classes::latest()->get();

        $classes = Classes::latest()->get()->map(function ($row) {
            return [
                'class_id'           => 'CLS-' . str_pad($row->id, 4, '0', STR_PAD_LEFT), // 1 -> CLS-0001
                'class_name'         => $row->title,        // or custom text like "风水入门 · Feng Shui Basics"
                'program_name'       => 'Feng Shui',        // static or from another column/table
                'enrolled'           => (bool) $row->is_active,
                'progress'           => 42,                 // replace with real calculation
                'duration_total_min' => 320,                // replace with real value
                'time_spent_min'     => 118,                // replace with real value
                'popularity'         => 96,                 // replace with real value
            ];
        });

        return view('students.class', ['classes' => $classes]);

    }
}
