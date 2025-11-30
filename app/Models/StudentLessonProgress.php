<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lesson;

class StudentLessonProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'student_id', 'class_id', 'lesson_id', 'status', 'started_at', 'completed_at', 'progress_percentage', 'is_completed', 'last_accessed_at'
    ];

    public function student() { 
        return $this->belongsTo(User::class, 'student_id'); 
    }
    public function lesson()  { 
        return $this->belongsTo(Lesson::class, 'lesson_id'); 
    }
}
