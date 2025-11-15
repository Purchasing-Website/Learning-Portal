<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\User;

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
}
