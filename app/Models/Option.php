<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Option extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
        'is_active',
        'created_by',
        'updated_by'
    ];
    
    public function question() { 
        return $this->belongsTo(Question::class); 
    }
}
