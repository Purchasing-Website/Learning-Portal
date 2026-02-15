<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classes;
use App\Models\Course;

class ClasssController extends Controller
{
    public function index($id)
    {
        // Retrieve classes with pagination (10 per page)
        $classes = null;
        $course = null;
        $show = null;
        if($id === 'all'){
            $classes = Classes::withCount('enrollments')
                ->orderBy('updated_at', 'desc')->get();
            $show='all';
        }
        else{
            $course = Course::with(['classes' => function ($q) {
                    $q->withCount('enrollments')
                    ->orderBy('class_course.sequence_order');
                }])->findOrFail($id);
            $show='class';
        }

        // Get all courses for potential use in the view
        $courses = Course::all();

        // Pass data to the view
        return view('admins.classes.class', compact('show','classes','course', 'courses'));
    }

    public function store(Request $request)
    {   
        // Validate incoming request data of the Classes
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'course_id' => 'required|integer',
        ]);
        // Create new Classs
        $class = Classes::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'image' => null, // Placeholder for image handling
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $class->courses()->attach($request->course_id, [
            'sequence_order' => 1,
        ]);

        return back()->with('success', 'Classs created successfully.');
    }    

    // Get Classes details for edit modal
    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        return response()->json($class);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = Classes::findOrFail($id);
        $class->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Classs updated successfully.']);

    }

    public function toggleStatus($id)
    {
        $class = Classes::findOrFail($id);
        $class->is_active = !$class->is_active;; // flip true/false
        $class->save();

        return response()->json([
            'success' => true,
            'is_active' => $class->is_active,
        ]);
    }

    public function loadAvailableCourses($id)
    {
        $class = Classes::with('courses')->findOrFail($id);
        
        $assignedCourseIds = $class->courses->pluck('id')->toArray();

        $availableCourses = Course::whereNotIn('id', $assignedCourseIds)->get();

        // Next sequence for this class within any course
        $nextSequence = $class->courses->count() + 1;

        return response()->json([
            'available' => $availableCourses,
            'nextSequence' => $nextSequence,
        ]);
    }

    public function assignCourse(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'sequence' => 'required|integer|min:1'
        ]);

        $class = Classes::findOrFail($id);

        $class->courses()->attach($request->course_id, [
            'sequence_order' => $request->sequence,
        ]);

        return response()->json(['success' => true, 'message' => 'Class assigned to course.']);
    }

    public function loadAssignedCourses($id)
    {
        $class = Classes::with(['courses' => function($q) {
            $q->withPivot('sequence_order', 'is_active', 'created_by', 'updated_by')
            ->with('creator:id,name');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $class->courses
        ]);
    }

    public function softUnassignCourse($classId, $courseId)
    {
        $class = Classes::findOrFail($classId);

        // Update the pivot record to mark as inactive instead of deleting
        $updated = $class->courses()
            ->updateExistingPivot($courseId, [
                'is_active' => false,
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);

        if ($updated) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }


    public function destroy($id)
    {
        // Find the Classs by ID
        $class = Classes::findOrFail($id);

        // Delete the Classs
        $class->delete();

        return back()->with('success', 'Classs deleted successfully.');
    }   
}
