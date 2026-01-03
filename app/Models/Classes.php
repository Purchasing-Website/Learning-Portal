<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentLessonProgress;

class Classes extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
        'created_by',
        'updated_by',
        'quiz_id',
        'pass_score'
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class,  'enrollments', 'class_id', 'student_id')
                    ->withPivot('status', 'progress', 'enrolled_at')
                    ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'class_course', 'class_id', 'course_id')
                    ->withPivot('sequence_order', 'is_active', 'created_by', 'updated_by')
                    ->withTimestamps();
    }

    public function progressForStudent($studentId)
    {
        return StudentLessonProgress::where('student_id', $studentId)
            ->where('class_id', $this->id)
            ->first();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }
}
