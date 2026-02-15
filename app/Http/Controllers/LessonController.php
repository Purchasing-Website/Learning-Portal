<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Classes;
use App\Enums\ContentType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index($id)
    {
        $contentTypes = ContentType::cases();

        $lessons = null;

        // Retrieve Lessons with pagination (10 per page)
        if($id === 'all'){
            $lessons = Lesson::orderBy('updated_at', 'desc')->get();
        }
        else{
            $lessons = Lesson::orderBy('updated_at', 'desc')->where('class_id',$id)->get();
        }
        

        $classes = Classes::select('id', 'title')->where('is_active', true)->get();

        // Pass data to the view
        return view('admins.lessons.lesson', compact('lessons', 'classes', 'contentTypes'));
    }

    public function store(Request $request)
    { 
      
        // Validate incoming request data of the Lesson
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'duration' => 'nullable|integer',
            'content_type' => ['required', new Enum(ContentType::class)],
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'source_url' => 'nullable|url',
        ]);

        // if ($validatedData['content_type'] === ContentType::Document->value && !$request->hasFile('file')) {
        //     return back()->withErrors(['file' => 'Please upload a document file.'])->withInput();
        // }

        // if ($validatedData['content_type'] === ContentType::Video->value && !$request->filled('source_url')) {
        //     return back()->withErrors(['source_url' => 'Please provide a valid video URL.'])->withInput();
        // }

        $sourcePath = null;
        
        $sequence = Lesson::latest('sequence')->where('class_id',$validatedData['class_id'])->first();

        //dd($sequence);

        // Create new Lesson
        $lesson = Lesson::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            //'image' => null, // Placeholder for image handling
            'content_type' => $validatedData['content_type'],
            'source_url' => $validatedData['source_url'],
            'duration' => $validatedData['duration'],
            'sequence' => ($sequence->sequence ?? 0) + 1,
            'is_active' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'class_id' => $validatedData['class_id'],
        ]);

        // if ($validatedData['content_type'] === ContentType::Document->value && $request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $originalName = $file->getClientOriginalName();

        //     // lessonMaterial naming convention
        //     $filename = 'Lesson_' . $lesson->id . '_' . Str::slug($lesson->title) . '_' . $originalName;

        //     $path = $file->storeAs('lesson/lessonMaterial', $filename, 'public');

        //     $lesson->content_type = ContentType::Document->value;

        //     $lesson->source_url = $path;
        // }

        // ✅ Step 5: Handle video URL
        // if ($validatedData['content_type'] === ContentType::Video->value) {

        //     $lesson->content_type = ContentType::Video->value;
        //     $lesson->source_url = $validatedData['source_url'];
        // }

        // ✅ Step 6: Handle optional background image
        if ($request->hasFile('image')) {
            $bgFile = $request->file('image');
            $originalName = $bgFile->getClientOriginalName();

            $bgFilename = 'LessonImage_' . $lesson->id . '_' . Str::slug($lesson->title) . '_' . $originalName;
            $bgPath = $bgFile->storeAs('lesson/lessonImage', $bgFilename, 'public');

            $lesson->background_image = 'storage/' . $bgPath;
        }

        // ✅ Step 7: Save final record
        $lesson->save();

        return back()->with('success', 'Lesson created successfully.');
    }    

    // Get Lesson details for edit modal
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        
        // Include enum content types for dropdown
        $contentTypes = collect(\App\Enums\ContentType::cases())->map(function ($type) {
            return [
                'name' => $type->name,
                'value' => $type->value,
            ];
        });

        $filePath = $lesson->source_url;
        $fileExists = false;
        $fileUrl = null;

        // Normalize path if it includes "storage/"
        $normalizedPath = str_replace('storage/', '', $filePath);

        if ($filePath && Storage::disk('public')->exists($normalizedPath)) {
            $fileExists = true;
            $fileUrl = asset('storage/' . $normalizedPath);
        }
        //dd( $normalizedPath);
        return response()->json(['lesson' => $lesson, 'content_types' => $contentTypes, 'fileUrl' => $fileUrl,'class_title'=>$lesson->class->title]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|numeric|min:1',
            'content_type' => ['required', new Enum(ContentType::class)],
            'source_url' => 'required|url',
            'video_url' => 'nullable|url',
            'material' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'lesson_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $lesson = Lesson::findOrFail($id);
        $lesson->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'duration' => $validatedData['duration'],
            'content_type' => $validatedData['content_type'],
            'source_url' => $validatedData['source_url'],
            'updated_by' => Auth::id(),
        ]);

        // === Handle Lesson Image (optional) ===
        // if ($request->hasFile('lesson_image')) {
        //     // Delete old image if exists
        //     if ($lesson->lesson_image && Storage::disk('public')->exists($lesson->lesson_image)) {
        //         Storage::disk('public')->delete($lesson->lesson_image);
        //     }

        //     $image = $request->file('lesson_image');
        //     $imagePath = 'lesson/lessonImage';
        //     $imageName = 'LessonImage_' . $lesson->id . '_' . str_replace(' ', '_', $lesson->name) . '_' . $image->getClientOriginalName();
        //     $lessonImagePath = $image->storeAs($imagePath, $imageName, 'public');

        //     $lesson->lesson_image = $lessonImagePath;
        // }

        //=== Handle Content Type ===
        // if ($validatedData['content_type'] === ContentType::Document->value) {
        //     // Handle document upload
        //     if ($request->hasFile('material')) {
        //         // Delete old material if exists
        //         if ($lesson->source_url && Storage::disk('public')->exists($lesson->source_url)) {
        //             Storage::disk('public')->delete($lesson->source_url);
        //         }

        //         $file = $request->file('material');
        //         $originalName = $file->getClientOriginalName();

        //         // lessonMaterial naming convention
        //         $filename = 'Lesson_' . $lesson->id . '_' . Str::slug($lesson->title) . '_' . $originalName;

        //         $path = $file->storeAs('lesson/lessonMaterial', $filename, 'public');

        //         $lesson->source_url = 'storage/' . $path;
        //     }
        // } elseif ($validatedData['content_type'] === ContentType::Video->value) {
            
        //     // If switching from document to video, delete old file
        //     if ($lesson->source_url && Storage::disk('public')->exists($lesson->source_url)) {
        //         Storage::disk('public')->delete($lesson->source_url);
        //     }

        //     $lesson->source_url = $validated['video_url'] ?? null;
        // }

        return response()->json(['success' => true, 'message' => 'Lesson updated successfully.']);

    }

    public function toggleStatus($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->is_active = !$lesson->is_active;; // flip true/false
        $lesson->save();

        return response()->json([
            'success' => true,
            'is_active' => $lesson->is_active,
        ]);
    }

    public function updateSequence(Request $req)
    {
        // 1. Validate the array structure
        $validated = Validator::make($req->all(), [
            'sequences' => 'required|array',
            'sequences.*.id' => 'required|integer',
            'sequences.*.sequence' => 'required|integer|min:1',
        ]);

        $validated->after(function ($validated) use ($req) {
            $items = $req->input('sequences', ['sequence']);
            
            // 1. Extract just the 'sequence' values and ensure they are integers
            $seqValues = collect($items)->pluck('sequence')->map(fn($v) => (int)$v);

            // 2. Check for duplicates in the sequence list
            if ($seqValues->count() !== $seqValues->unique()->count()) {
                $validated->errors()->add('sequences', 'Duplicate sequence numbers detected.');
                return;
            }

            // 3. Check for gaps (1 to N)
            // Sort values so [3, 1, 2] becomes [1, 2, 3] for comparison
            $sorted = $seqValues->sort()->values()->toArray();
            $expected = range(1, count($items));

            if ($sorted !== $expected) {
                $validated->errors()->add('sequences', 'Sequences must be consecutive (1, 2, 3...) with no gaps.');
            }
        });

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ]);
        }

        $items = $req->input('sequences');

        // 2. Perform the high-performance update
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                Lesson::where('id', $item['id'])
                    ->update(['sequence' => $item['sequence']]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Lessons sequence update successful',
        ]);
    }

    public function destroy($id)
    {
        // Find the Lesson by ID
        $lesson = Lesson::findOrFail($id);

        // Delete the Lesson
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully.');
    }   
}
