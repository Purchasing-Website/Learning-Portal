<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Option;

class OptionController extends Controller
{
    public function index($questionId)
    {
        $question = Question::findOrFail($questionId);
        $options = Option::where('question_id', $questionId)->paginate(10);

        return view('admins.options.option_view', compact('question', 'options'));
    }

    public function edit($id)
    {
        $option = Option::findOrFail($id);
        return response()->json($option);
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'option_text' => 'required|string',
                'is_correct' => 'required|boolean',
                'question_id' => 'required|integer|exists:questions,id',
                'is_active' => 'required|boolean',
            ]);
            //dd($validated);
            $option = Option::create([
                'question_id' => $validated['question_id'],
                'option_text' => $validated['option_text'],
                'is_correct' => true,
                'is_active' => $validated['is_active'],
                'created_by' => Auth::id(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Option save successful', 'option' => $option]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'option_text' => 'required|string',
                'is_correct' => 'required|boolean',
                'question_id' => 'required|integer|exists:questions,id',
                'is_active' => 'required|boolean',
            ]);

            $option = Option::findOrFail($id);

            $option -> update([
                'question_id' => $validated['question_id'],
                'option_text' => $validated['option_text'],
                'is_correct' => $validated['is_correct'],
                'is_active' => $validated['is_active'],
                'update_by' => Auth::id(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Option save successful', 'option' => $option]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        $option = Option::findOrFail($id);
        $option->is_active = !$option->is_active;
        $option->updated_by = Auth::id();
        $option->save();

        return response()->json(['success' => true, 'is_active' => $option->is_active]);
    }
}
