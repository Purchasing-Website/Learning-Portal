<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Enums\QuestionType;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\Option;

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

    public function QuizIndex(){
        $classes = Classes::all();
        $QuestionTypes=collect(QuestionType::cases())->map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ]);
        
        $formattedClasses = $classes->map(function ($item) {
            return [
                // Using sprintf to create the "CLS-" prefix with padding
                'classId'   => $item->id, 
                'className' => $item->title,
            ];
        });
        //dd($quiz);
        return view('Quiz_Builder_1',['questionTypes'=>$QuestionTypes, 'classes' => $formattedClasses]);
    }

    public function getQuiz($id){
        
        $classes = Classes::where('id', $id)->first();
        //dd($classes->quiz_id);
        $quiz = Quiz::with('questions.options')->find($classes->quiz_id);
        $QuestionTypes=collect(QuestionType::cases())->map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ]);
        
        // $formattedClasses = $classes->map(function ($item) {
        //     return [
        //         // Using sprintf to create the "CLS-" prefix with padding
        //         'classId'   => $item->id, 
        //         'className' => $item->title,
        //     ];
        // });
        return response()->json(['quiz'=>$quiz,'questionTypes'=>$QuestionTypes]);
    }

    public function saveQuiz(Request $request)
    {
        //dd($request);
        // Wrap everything in a transaction to ensure data integrity
        return DB::transaction(function () use ($request) {
            // 1. Update or Create the Quiz
            // Note: Mapping 'description' from JSON to 'instructions' or 'description' in DB
            $quiz = Quiz::updateOrCreate(
                ['id' => $request->quizId],
                [
                    //'class_id'     => $request->class_id,
                    'title'        => $request->title,
                    'description'  => $request->instructions,
                    'quizType'     => $request->type,
                    'pass_score'   => $request->passScore,
                    'is_active'    => $request->status = 'active' ? 1 : 0,
                ]
            );

            if ($quiz->wasRecentlyCreated) {
               $classes = Classes::where('id', $request->classId)->first();
               $classes->quiz_id = $quiz->id;
               $classes->save();
            }

            // 2. Identify and DELETE removed questions
            // Collect all IDs from the incoming JSON (filtering out nulls/temp IDs)
            $incomingQuestionIds = collect($request->questions)
                ->pluck('id')
                ->filter(fn($id) => is_numeric($id))
                ->toArray();

            // Any question in DB but NOT in the JSON list is deleted
            $quiz->questions()->whereNotIn('id', $incomingQuestionIds)->delete();

            // 3. Process Questions
            foreach ($request->questions as $qData) {
                // Determine if we update existing or create new
                $questionId = (isset($qData['id']) && $qData['id'] > 0) ? $qData['id'] : null;

                $question = Question::updateOrCreate(
                    ['id' => $questionId],
                    [
                        'quiz_id'      => $quiz->id,
                        'question'     => $qData['text'],
                        'questiontype' => $qData['type'],
                        'points'       => $qData['points'] ?? 1,
                        //'sequence_no'  => $qData['sequence_no'],
                        //'explanation'  => $qData['explanation'] ?? '',
                    ]
                );

                // 4. Identify and DELETE removed options for this specific question
                $incomingOptionIds = collect($qData['options'])
                    ->pluck('id')
                    ->filter(fn($id) => is_numeric($id))
                    ->toArray();

                $question->options()->whereNotIn('id', $incomingOptionIds)->delete();

                // 5. Sync Options
                foreach ($qData['options'] as $index => $oData) {
                    
                    // Logic for Correct Answer (Single vs Multiple)
                    // We compare the current loop index with correct_index or correct_indexes array
                    $isCorrect = (isset($qData['correctIndex']) && (int)$qData['correctIndex'] === $index) || 
                                 (isset($qData['correctIndexes']) && in_array($index, (array)$qData['correctIndexes']));

                    $optionId = (isset($oData['id']) && $oData['id'] > 0) ? $oData['id'] : null;

                    Option::updateOrCreate(
                        ['id' => $optionId],
                        [
                            'question_id' => $question->id,
                            'option_text' => $oData['option_text'] ?? $oData['text'],
                            //'sequence_no' => $oData['sequence_no'],
                            'is_correct'  => $isCorrect,
                            //'is_active'   => $oData['is_active'] ?? 1,
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Quiz synchronized successfully',
                'data'    => $quiz->load('questions.options')
            ]);
        });
    }
    
}
