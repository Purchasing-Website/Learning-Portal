<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Question;
use App\Enums\QuestionType;
use Illuminate\Validation\Rules\Enum;

class QuestionController extends Controller
{   
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = Question::where('quiz_id', $quizId)->paginate(10);
        $questionTypes = QuestionType::cases();
        //dd($questionTypes);
        return view('admins.questions.question_view', compact('quiz', 'questions', 'questionTypes'));
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return response()->json($question);
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'question' => 'required|string',
                'questiontype' => ['required', new Enum(QuestionType::class)],
                'points' => 'nullable|integer|min:1',
                'quiz_id' => 'required|integer|exists:quizzes,id',
                'is_active' => 'required|boolean',
            ]);
            //dd($validated['is_active']);
            $question = Question::create([
                'quiz_id' => $validated['quiz_id'],
                'question' => $validated['question'],
                'questiontype' => $validated['questiontype'],
                'points' => $validated['points'],
                'is_active' => $validated['is_active'],
                'created_by' => Auth::id(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Question save successful', 'question' => $question]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'question' => 'required|string',
                'questiontype' => ['required', new Enum(QuestionType::class)],
                'points' => 'nullable|integer|min:1',
                'quiz_id' => 'required|integer|exists:quizzes,id',
                'is_active' => 'required|boolean',
            ]);

            $question = Question::findOrFail($id);

            $question -> update([
                'quiz_id' => $validated['quiz_id'],
                'question' => $validated['question'],
                'questiontype' => $validated['questiontype'],
                'points' => $validated['points'],
                'is_active' => $validated['is_active'],
                'updated_by' => Auth::id(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Question save successful', 'question' => $question]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        $quiz = Question::findOrFail($id);
        $quiz->is_active = !$quiz->is_active;
        $quiz->updated_by = Auth::id();
        $quiz->save();

        return response()->json(['success' => true, 'is_active' => $quiz->is_active]);
    }
}
