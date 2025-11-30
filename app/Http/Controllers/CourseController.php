<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Program;


class CourseController extends Controller
{
    public function index()
    {
        // Retrieve course with pagination (10 per page)
        $courses = Course::orderBy('created_at', 'desc')->paginate(10);

        //dd ($courses);

        // Retrieve all programs for potential use in the view
        $programs = Program::select('id', 'title')->where('is_active', true)->get();

        // Pass data to the view
        return view('admins.courses.course_view', compact('courses', 'programs'));
    }

    public function store(Request $request)
    {  
        // Validate incoming request data of the Course
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'program_id' => 'required|integer|exists:programs,id',
        ]);
        // Create new Course
        Course::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'program_id' => $validatedData['program_id'],
            'image' => null, // Placeholder for image handling
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Course created successfully.');
    }    

    // Get course details for edit modal
    public function edit($id)
    {
        $course = Course::findOrFail($id);

        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Course updated successfully.']);

    }

    public function toggleStatus($id)
    {
        $course = Course::findOrFail($id);
        $course->is_active = !$course->is_active;; // flip true/false
        $course->save();

        return response()->json([
            'success' => true,
            'is_active' => $course->is_active,
        ]);
    }


    public function destroy($id)
    {
        // Find the course by ID
        $course = Course::findOrFail($id);

        // Delete the course
        $course->delete();

        return back()->with('success', 'Course deleted successfully.');
    }   
}
