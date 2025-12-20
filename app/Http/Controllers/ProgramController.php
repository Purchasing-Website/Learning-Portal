<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index()
    {
        // Retrieve programs with pagination (10 per page)
        $programs = Program::orderBy('created_at', 'desc')->get();

        // Pass data to the view
        //return view('admins.programs.program_view', compact('programs'));
        return view('program', compact('programs'));
    }

    public function store(Request $request)
    {   
        //dd($request -> all());

        // Validate incoming request data of the program
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);
        // Create new program
        Program::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'image' => null, // Placeholder for image handling
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Program created successfully.');
    }    

    // Get program details for edit modal
    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return response()->json($program);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
        ]);
        
        $program = Program::findOrFail($id);
        $program->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
        ]);

        return response()->json(['success' => true, 'message' => 'Program updated successfully.']);

    }

    public function toggleStatus($id)
    {
        $program = Program::findOrFail($id);
        $program->is_active = !$program->is_active;; // flip true/false
        $program->save();

        return response()->json([
            'success' => true,
            'is_active' => $program->is_active,
        ]);
    }


    public function destroy($id)
    {
        // Find the program by ID
        $program = Program::findOrFail($id);

        // Delete the program
        $program->delete();

        return back()->with('success', 'Program deleted successfully.');
    }   
}  
