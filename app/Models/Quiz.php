<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Lesson;

class Quiz extends Model
{
    protected $fillable = [
      'title',
      'description',
      'quizType',
      'source_url',
      'is_active',
      'total_questions',
      'pass_score',
      'created_by',
      'updated_by'
    ];

    public function questions() { 
        return $this->hasMany(Question::class); 
    }
    
    // quiz -> many lessons
    public function lessons(){ 
        return $this->hasMany(Lesson::class); 
    } 
}
