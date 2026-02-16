<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Attach enrollment status to each student
        foreach ($students as $student) {
            $pivot = $class->students->firstWhere('id', $student->id);
            $student->enrollment_status = $pivot->pivot->status ?? null;
        }

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

    public function updateEnrollment(Request $request, $id)
    {
        $class = Classes::findOrFail($id);

        $studentId = $request->input('student_id', Auth::id());

        if (!$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not authenticated.',
            ], 401);
        }

        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid student.',
            ], 422);
        }

        $studentTierLevel = DB::table('tiers')
            ->where('id', $student->tierid)
            ->value('level');

        $classTierLevel = DB::table('tiers')
            ->where('id', $class->tier_id)
            ->value('level');

        if ($studentTierLevel === null || $classTierLevel === null) {
            return response()->json([
                'success' => false,
                'message' => 'Tier configuration is invalid.',
            ], 422);
        }

        if ((int) $classTierLevel > (int) $studentTierLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Student tier is not eligible for this class.',
            ], 403);
        }

        $existing = $class->students()
            ->where('users.id', $studentId)
            ->first();

        if ($existing) {
            if ($existing->pivot->status === 'active') {
                return response()->json([
                    'success' => true,
                    'message' => 'Student is already enrolled.',
                ]);
            }

            $class->students()->updateExistingPivot($studentId, [
                'status' => 'active',
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student enrollment reactivated successfully.',
            ]);
        }

        $class->students()->attach($studentId, [
            'status' => 'active',
            'progress' => 0,
            'enrolled_at' => now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student enrolled successfully.',
        ]);
    }
}
