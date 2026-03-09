<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Classes;
use App\Models\User;
use App\Models\Tier;
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

    public function tier(){
        $tiers = Tier::query()
            ->orderBy('level', 'asc')
            ->get();

        return view('admins.enrollments.enrollment', [
            'tiers' => $tiers,
            'within_tier' => collect(),
            'outside_tier' => collect(),
        ]);
    }

    public function filterTier($tier)
    {
        Tier::findOrFail($tier);

        $withinTier = User::query()
            ->where('role', 'student')
            ->select(['id', 'name', 'tierid'])
            ->where('tierid', (int) $tier)
            ->get();

        $outsideTier = User::query()
            ->where('role', 'student')
            ->select(['id', 'name', 'tierid'])
            ->where(function ($query) use ($tier) {
                $query->where('tierid', '!=', (int) $tier)
                    ->orWhereNull('tierid');
            })
            ->get();

        if (request()->ajax()) {
            return response()->json([
                'within_tier' => $withinTier->values(),
                'outside_tier' => $outsideTier->values(),
            ]);
        }

        $tiers = Tier::query()
            ->orderBy('level', 'asc')
            ->get();

        return view('admins.enrollments.enrollment', [
            'tiers' => $tiers,
            'within_tier' => $withinTier->values(),
            'outside_tier' => $outsideTier->values(),
        ]);
    }

    public function saveTierAssignments(Request $request, $tier)
    {
        $tierId = (int) $tier;
        Tier::findOrFail($tierId);

        $validated = $request->validate([
            'assigned_ids' => ['nullable', 'array'],
            'assigned_ids.*' => ['integer'],
        ]);

        $assignedIds = collect($validated['assigned_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        User::query()
            ->where('role', 'student')
            ->where('tierid', $tierId)
            ->when(!empty($assignedIds), function ($query) use ($assignedIds) {
                $query->whereNotIn('id', $assignedIds);
            })
            ->update(['tierid' => 1]);

        if (!empty($assignedIds)) {
            User::query()
                ->where('role', 'student')
                ->whereIn('id', $assignedIds)
                ->update(['tierid' => $tierId]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tier assignments saved successfully.',
        ]);
    }
    
}
