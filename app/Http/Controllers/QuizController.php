<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->paginate(10);
        return view('admins.quizzes.quiz_view', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'quizType' => 'required|in:KnowledgeCheck,FinalQuiz',
            'source_url' => 'nullable|string|max:255',
            'pass_score' => 'required|numeric|min:0|max:100',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        $quiz = Quiz::create($validated);

        return response()->json(['success' => true, 'quiz' => $quiz]);
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        return response()->json($quiz);
    }

    public function toggleStatus($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->is_active = !$quiz->is_active;
        $quiz->save();

        return response()->json(['success' => true, 'is_active' => $quiz->is_active]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'quizType' => 'required|in:KnowledgeCheck,FinalQuiz',
            'source_url' => 'nullable|string|max:255',
            'pass_score' => 'required|numeric|min:0|max:100',
        ]);

        $quiz = Quiz::findOrFail($id);
        $quiz->update([
            ...$validated,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Quiz updated successfully']);
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quizzes.show', compact('quiz'));
    }
    
}
