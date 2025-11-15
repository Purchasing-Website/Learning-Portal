<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classes;

class ClasssController extends Controller
{
    public function index()
    {
        // Retrieve classes with pagination (10 per page)
        $classes = Classes::orderBy('created_at', 'desc')->paginate(10);

        // Pass data to the view
        return view('admins.classes.class_view', compact('classes'));
    }

    public function store(Request $request)
    {   
        // Validate incoming request data of the Classes
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);
        // Create new Classs
        Classes::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'image' => null, // Placeholder for image handling
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
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


    public function destroy($id)
    {
        // Find the Classs by ID
        $class = Classes::findOrFail($id);

        // Delete the Classs
        $class->delete();

        return back()->with('success', 'Classs deleted successfully.');
    }   
}
