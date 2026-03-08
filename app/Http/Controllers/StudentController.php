<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classes; // your class model name
use App\Models\Lesson;
use App\Models\StudentLessonProgress;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // 1. Show assigned classes
    public function assignedClasses()
    {
        $student = Auth::user();

        // Fetch all enrolled classes (active, completed, dropped)
        $classes = $student->enrolledClasses()->withPivot('status', 'progress', 'enrolled_at')->get();

        return view('students.class_index', compact('classes'));
    }

    // 2. Show lessons for a class
    public function lessons($id)
    {
        $studentId = Auth::id();
        $class = Classes::with(['lessons' => function($q) {
            $q->orderBy('sequence');
        }])->findOrFail($id);

        $progress = StudentLessonProgress::where('student_id', $studentId)
                        ->where('class_id', $class->id)
                        ->pluck('progress_percentage','lesson_id');

        return view('students.lesson_show', compact('class', 'progress'));
    }

    // 3. Start lesson
    public function startLesson($id)
    {
        $studentId = Auth::id();
        $lesson = Lesson::findOrFail($id);

        // Create progress if not exists
        $progress = StudentLessonProgress::updateOrCreate(
            ['student_id' => $studentId, 
            'lesson_id' => $lesson->id],
            [
            'class_id' => $lesson->class_id, 
            'progress_percentage' => 0, 
            'is_completed' => false, 
            'last_accessed_at' => now()]
        );
        //dd($progress);
        return back();
    }

    // 4. Update progress (AJAX)
    public function updateProgress(Request $request, $id)
    {
        $studentId = Auth::id();
        $lesson = Lesson::findOrFail($id);

        //dd($lesson->class_id);

        $request->validate([
            //'progress_percentage' => 'required|numeric|min:0|max:100',
            'is_completed' => 'nullable|boolean'
        ]);

        $progress = StudentLessonProgress::updateOrCreate(
            ['student_id' => $studentId, 
                'lesson_id' => $lesson->id,],
            ['class_id' => $lesson->class_id,
                'progress_percentage' => 100,
                'is_completed' => true,
                'last_accessed_at' => now()
            ]
        );

        // Update overall class progress in Enrollment
        $totalLessons = $lesson->class->lessons()->count();
        $completedLessons = StudentLessonProgress::where('student_id', $studentId)
                            ->where('class_id', $lesson->class_id)
                            ->where('is_completed', true)
                            ->count();

        $classProgress = round(($completedLessons / $totalLessons) * 100, 2);

        $lesson->class->students()->updateExistingPivot($studentId, [
            'progress' => $classProgress,
            'status' => $classProgress == 100 ? 'completed' : 'active'
        ]);

        return response()->json(['success' => true, 'message' => 'Progress updated', 'progress' => $classProgress]);
    }

    public function completeLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $studentId = Auth::id();
        $lessonId = $request->lesson_id;
        $classId = $request->class_id;
        $lesson = Lesson::findOrFail($request->lesson_id);

        // 1. Mark lesson as completed
        StudentLessonProgress::updateOrCreate(
            [
                'student_id' => $studentId,
                'lesson_id' => $lessonId,
            ],
            [
                'class_id' => $classId,
                'progress_percentage' => 100,
                'is_completed' => true,
                'last_accessed_at' => now(),
            ]
        );

        // Optional: Check does this lesson got quiz
        $knowledgeCheck = $lesson->knowledge_check_id;
        if ($knowledgeCheck) {

            return redirect()->route('student.lesson.knowledgeCheck', [$lesson->knowledge_check_id, 'Knowledge Check']);

        }

        return $this->goToNextLessonOrCompleteClass($lesson);        
    }

    public function goToNextLessonOrCompleteClass(Lesson $currentLesson)
    {
        $studentId = Auth::id();

        // 2. Find the next lesson
        $class = $currentLesson->class;

        // Find next lesson by sequence
        $nextLesson = Lesson::where('class_id', $class->id)
            ->where('sequence', '>', $currentLesson->sequence)
            ->orderBy('sequence')
            ->first();

        if ($nextLesson) {
            // Update overall class progress in Enrollment
            $totalLessons = $currentLesson->class->lessons()->count();
            $completedLessons = StudentLessonProgress::where('student_id', $studentId)
                                ->where('class_id', $currentLesson->class_id)
                                ->where('is_completed', true)
                                ->count();

            $classProgress = round(($completedLessons / $totalLessons) * 100, 2);

            Enrollment::where('student_id', $studentId)
            ->where('class_id', $currentLesson->class_id)
            ->update([
                'status' => $classProgress == 100 ? 'completed' : 'active',
                'progress' => $classProgress,
            ]);
            
            //return redirect()->route('student.lesson.start', $nextLesson->id)->with('message', 'Lesson completed. Proceeding to next lesson.');
            return response()->json([
                'success' => true,
                'message' => 'Lesson completed. Proceeding to next lesson.'
            ]);
        }

        // Check does student completed the Final Quiz with passed result
        $StudentFinalQuiz = Enrollment::where('student_id', $studentId)
                            ->where('class_id', $class->id)
                            ->first();
    
        if ($class->quiz_id && $StudentFinalQuiz->status != 'completed') {
            return redirect()
                ->route('student.lesson.knowledgeCheck', [$class->quiz_id , 'Final Quiz'] )
                ->with('message', 'All lessons completed. Please proceed to the final quiz.');
        }

        $totalLessons = $class->lessons()->count();
        $lessonProgress = StudentLessonProgress::where('student_id', $studentId)
            ->where('class_id', $class->id)
            ->selectRaw('COUNT(*) as attended_lessons')
            ->selectRaw('SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed_lessons')
            ->first();

        $attendedLessons = (int) ($lessonProgress->attended_lessons ?? 0);
        $completedLessons = (int) ($lessonProgress->completed_lessons ?? 0);
        $classProgress = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100, 2)
            : 0;

        if ($totalLessons === 0 || $attendedLessons < $totalLessons || $completedLessons < $totalLessons) {
            Enrollment::where('student_id', $studentId)
                ->where('class_id', $class->id)
                ->update([
                    'status' => 'active',
                    'progress' => $classProgress,
                ]);

            return response()->json([
                'success' => false,
                'message' => 'Class not complete. Please attend and complete all lessons first.'
            ]);
        }

        // 3. No more lessons → mark class completed
        Enrollment::where('student_id', $studentId)
            ->where('class_id', $class->id)
            ->update([
                'completed_at' => now(),
                'status' => 'completed',
                'progress' => 100,
            ]);
        
        //return redirect()->route('student.class.lessons',$class->id)->with('message', 'Congratulations! You have completed the class.');
        return response()->json([
            'success' => true,
            'message' => 'Congratulations! You have completed the class.'
        ]);

    }

    public function knowledgeCheck($id ,$mode)
    {
        $knowledgeCheck = Quiz::with(['questions.options'])->findOrFail($id);

        return view('students.knowledgeCheck', compact('knowledgeCheck', 'mode'));
    }   

    public function submitKnowledgeCheck(Request $request, $id, $mode)
    {
        $studentId = Auth::id();
        $check = Quiz::with('questions.options')->findOrFail($id);

        $submitted = $request->input('answers', []);
        $totalQuestions = $check->questions->count();
        $correctCount = 0;

        foreach ($check->questions as $question) {

            /** -------------------------------
             *  TRUE/FALSE QUESTIONS
             * --------------------------------*/
            if ($question->questiontype === 'true_false') {

                $correctOption = $question->options->where('is_correct', 1)->first();
                $studentAnswer = $submitted[$question->id] ?? null;

                if ($studentAnswer === strtolower($correctOption->text)) {
                    $correctCount++;
                }

                continue;
            }

            /** -------------------------------
             *  SINGLE & MULTIPLE CHOICE
             * --------------------------------*/
            $correctOptions = $question->options
                ->where('is_correct', 1)
                ->pluck('id')
                ->toArray();

            $studentAnswer = $submitted[$question->id] ?? [];

            if (!is_array($studentAnswer)) {
                $studentAnswer = [$studentAnswer];
            }

            sort($correctOptions);
            sort($studentAnswer);

            if ($correctOptions == $studentAnswer) {
                $correctCount++;
            }
        }

        $score = round(($correctCount / $totalQuestions) * 100, 2);

        // Find lesson for redirect
        $lesson = Lesson::where('knowledge_check_id', $id)->first();

        if ($score >= 80) {
            if($mode === 'Final Quiz'){
                // Mark class as completed
                $class = $lesson->class;
                Enrollment::where('student_id', $studentId)
                    ->where('class_id', $class->id)
                    ->update([
                        'completed_at' => now(),
                        'status' => 'completed',
                        'progress' => 100,
                    ]);

                return redirect()
                    ->route('student.class.lessons', $class->id)
                    ->with('message', "Congratulations! You have passed the final quiz with a score of $score% and completed the class.");
            }
            return $this->goToNextLessonOrCompleteClass($lesson);
        }

        return redirect()
            ->back()
            ->with('error', "You scored $score%. You need at least 80% to pass.");
    }

    public function getClass($classid)
    {
        $class = Classes::with(['tier:id,name', 'courses:id,title'])->findOrFail($classid);

        $lessons = Lesson::where('class_id', $classid)
            ->where('is_active',1)
            ->orderBy('sequence')
            ->get(['id', 'title', 'duration', 'sequence']);

        $lessonStatuses = collect();
        $studentId = Auth::id();
        $studentEnroll = false;

        if ($studentId) {
            $isEnrolled = Enrollment::where('student_id', $studentId)
                ->where('class_id', $classid)
                ->exists();

            if ($isEnrolled) {
                $studentEnroll = true;
                $lessonStatuses = StudentLessonProgress::where('student_id', $studentId)
                    ->where('class_id', $classid)
                    ->get(['lesson_id', 'progress_percentage', 'is_completed', 'created_at', 'last_accessed_at'])
                    ->keyBy('lesson_id');
            }
        }

        $lessons->transform(function ($lesson) use ($lessonStatuses) {
            $progress = $lessonStatuses->get($lesson->id);
            //dd($progress);
            $status = 'not_started';

            if ($progress) {
                if (
                    $progress->is_completed ||
                    (float) $progress->progress_percentage >= 100 ||
                    $progress->status === 'completed'
                ) {
                    $status = 'completed';
                } elseif (
                    $progress->last_accessed_at ||
                    (float) $progress->progress_percentage > 0 ||
                    in_array($progress->status, ['in_progress', 'active', 'started'], true)
                ) {
                    $status = 'in_progress';
                }
            }
            
            $lesson->status = $status;
            //dd($lesson);
            return $lesson;
        });

        $totalLessonDuration = (int) $lessons->sum(function ($lesson) {
            return (int) ($lesson->duration ?? 0);
        });
        $completedLessonsCount = $lessons->where('status', 'completed')->count();

        $classDetail = (object) [
            'classId' => $class->id,
            'class_name' => $class->title,
            'tier_name' => $class->tier->name ?? '',
            'course_name' => optional($class->courses->first())->title ?? '',
            'total_lessons' => $lessons->count(),
            'completed_lessons' => $completedLessonsCount,
            'total_lesson_duration' => $totalLessonDuration,
        ];

        return view('students.class_details', compact('lessons', 'classDetail','studentEnroll'));
    }

    public function getProfile($id){
        $user = User::findOrFail(decrypt($id));

        return view('students.profile', compact('user'));
    }

    public function saveProfile(Request $request, $id)
    {
        try {
            $userId = decrypt($id);
        } catch (\Throwable $e) {
            abort(404, 'Invalid profile identifier.');
        }

        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => ['nullable', 'regex:/^\+60(1\d{8,9}|[3-9]\d{7,8})$/'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->updated_by = Auth::id();
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'data' => $user->only(['id', 'name', 'email', 'date_of_birth', 'gender', 'phone']),
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function myLearning(){
        $studentId = Auth::id();
        $progressByClass = StudentLessonProgress::query()
            ->from('student_progress')
            ->leftJoin('lessons', 'student_progress.lesson_id', '=', 'lessons.id')
            ->where('student_progress.student_id', $studentId)
            ->groupBy('student_progress.class_id')
            ->selectRaw('
                student_progress.class_id,
                MAX(student_progress.last_accessed_at) as last_access,
                COALESCE(ROUND(SUM(COALESCE(lessons.duration, 0) * (student_progress.progress_percentage / 100))), 0) as time_spent_min
            ')
            ->get()
            ->keyBy('class_id');

        $enrolledClasses = Enrollment::query()
            ->where('student_id', $studentId)
            ->with([
                'class' => function ($query) {
                    $query->select('id', 'title')
                        ->withSum('lessons as duration_total_min', 'duration')
                        ->with(['courses' => function ($courseQuery) {
                            $courseQuery->select('courses.id', 'courses.title');
                        }]);
                },
            ])
            ->get(['class_id', 'status', 'progress', 'enrolled_at'])
            ->map(function ($enrollment) use ($progressByClass) {
                $class = $enrollment->class;
                $classProgress = $progressByClass->get($enrollment->class_id);
                $progressValue = (float) ($enrollment->progress ?? 0);
                $normalizedStatus = $progressValue >= 100
                    ? 'completed'
                    : ($progressValue > 0 ? 'in_progress' : 'not_started');

                return [
                    'id' => 'CLS-' . str_pad((string) $enrollment->class_id, 4, '0', STR_PAD_LEFT),
                    'classId' => $enrollment->class_id,
                    'course_name' => optional($class?->courses?->first())->title ?? '',
                    'class_name' => $class->title ?? '',
                    'date_enrolled' => $enrollment->enrolled_at
                        ? \Carbon\Carbon::parse($enrollment->enrolled_at)->format('Y-m-d\TH:i:s')
                        : null,
                    'instructor' => 'Hao Lin Academy',
                    'status' => $normalizedStatus,
                    'progress' => (int) round(max(0, min(100, $progressValue))),
                    'duration_total_min' => (int) ($class->duration_total_min ?? 0),
                    'time_spent_min' => (int) ($classProgress->time_spent_min ?? 0),
                    'last_access' => !empty($classProgress->last_access)
                        ? \Carbon\Carbon::parse($classProgress->last_access)->format('Y-m-d\TH:i:s')
                        : null,
                ];
            })
            ->values();

        return view('students.my_learning', compact('enrolledClasses'));
    }

    public function contentDisplayer($classId)
    {
        $class = Classes::findOrFail($classId);
    
        $lessons = Lesson::where('class_id', $classId)
            ->where('is_active', 1)
            ->orderBy('sequence')
            ->get();
    
        // Convert DB rows into the structure your ContentDisplayer.js expects
        $items = $lessons->map(function ($l) {
            $type = strtolower(trim($l->content_type ?? ''));
    
            // Map DB content_type -> js type (image/pdf/video)
            $mappedType = match ($type) {
                'image' => 'image',
                'pdf'   => 'pdf',
                'video' => 'video',
                default => $type, // fallback
            };
    
            return [
                'id'        => 'lesson-' . $l->id,
                'title'     => $l->title ?? ('Lesson ' . $l->id),
                'kind'      => 'lesson',
                'type'      => $mappedType,
                'src'       => $l->source_url,     // must be valid URL
                'completed' => true,
            ];
        })->values();
    
        $classData = [
            'classId'   => 'CLS-' . $class->id,
            'className' => $class->title ?? ('Class ' . $class->id),
            'user'      => ['name' => auth()->user()->name ?? 'User'],
            'footer'    => 'Hao Lin© Brand 2025',
            'items'     => $items,
        ];
    
        return view('students.content_displayer', compact('classData'));
    }

    public function contentDisplayerv2($classId)
    {
        $class = Classes::findOrFail($classId);
        $studentId = Auth::id();
    
        $lessons = Lesson::where('class_id', $classId)
            ->where('is_active', 1)
            ->orderBy('sequence')
            ->get();

        $progressByLesson = StudentLessonProgress::query()
            ->where('student_id', $studentId)
            ->where('class_id', $classId)
            ->get(['lesson_id', 'is_completed', 'progress_percentage'])
            ->keyBy('lesson_id');

        $nextLessonId = null;
        $lastCompletedIndex = -1;

        foreach ($lessons as $index => $lesson) {
            $progress = $progressByLesson->get($lesson->id);
            //dd($progress);
            $isCompleted = (bool) ($progress && (
                $progress->is_completed ||
                (float) $progress->progress_percentage >= 100
            ));

            if ($isCompleted) {
                $lastCompletedIndex = $index;
            }
        }

        if ($lessons->isNotEmpty()) {
            $nextIndex = $lastCompletedIndex + 1;
            if ($nextIndex < $lessons->count()) {
                $nextLessonId = $lessons[$nextIndex]->id;
            }
        }
    
        // Convert DB rows into the structure your ContentDisplayer.js expects
        $items = $lessons->map(function ($l) use ($progressByLesson, $nextLessonId) {
            $type = strtolower(trim($l->content_type ?? ''));
            $progress = $progressByLesson->get($l->id);
    
            // Map DB content_type -> js type (image/pdf/video)
            $mappedType = match ($type) {
                'image' => 'image',
                'pdf'   => 'pdf',
                'video' => 'video',
                default => $type, // fallback
            };
    
            return [
                'id'        => 'lesson-' . $l->id,
                'title'     => $l->title ?? ('Lesson ' . $l->id),
                'kind'      => 'lesson',
                'type'      => $mappedType,
                'src'       => $l->source_url,     // must be valid URL
                'completed' => (bool) ($progress && (
                    $progress->is_completed &&
                    (float) $progress->progress_percentage >= 100
                )),
                //'completed' => true,
                'currentLesson' => $nextLessonId !== null && (int) $l->id === (int) $nextLessonId,
            ];
        })->values();

        $classData = [
            'classId'   => 'CLS-' . $class->id,
            'className' => $class->title ?? ('Class ' . $class->id),
            'user'      => ['name' => auth()->user()->name ?? 'User'],
            'footer'    => 'Hao Lin© Brand 2025',
            'items'     => $items,
        ];
    
        return view('students.content_displayer', compact('classData'));
    }
    
}
