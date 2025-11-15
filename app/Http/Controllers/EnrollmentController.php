<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    //index
    public function index($id)
    {   
        //dd($id); 
        $class = Classes::with(['students' => function($q) {
            $q->withPivot('status', 'progress', 'enrolled_at')->paginate(2);
        }])->findOrFail($id);

        $students = User::where('role', 'student')->get(); // adjust role check as needed

        //dd($students);

        return view('admins.enrollments.enrollment_view', compact('class','students'));
    }

    public function updateEnrollments(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        $selectedStudents = $request->input('student_ids', []);

        $currentEnrollments = $class->students()->pluck('users.id')->toArray();

        $studentsToDrop = array_diff($currentEnrollments, $selectedStudents);

        // ✅ Handle new or reactivated enrollments
        foreach ($selectedStudents as $studentId) {
            $existing = $class->students()
                ->where('users.id', $studentId)
                ->first();

            if ($existing) {
                // Reactivate if previously dropped
                if ($existing->pivot->status === 'dropped') {
                    $class->students()->updateExistingPivot($studentId, [
                        'status' => 'active',
                        'updated_by' => Auth::id(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // New enrollment
                $class->students()->attach($studentId, [
                    'status' => 'active',
                    'progress' => 0,
                    'enrolled_at' => now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        // ✅ Mark dropped students (instead of deleting)
        if (!empty($studentsToDrop)) {
            $class->students()->updateExistingPivot($studentsToDrop, [
                'status' => 'dropped',
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Enrollments updated successfully.',
        ]);
    }

}
