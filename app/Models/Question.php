<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz;
use App\Models\Option;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'questiontype',
        'source_url',
        'points',
        'is_active',
        'created_by',
        'updated_by'
    ];
    
    public function quiz() { 
        return $this->belongsTo(Quiz::class); 
    }
    
    public function options() { 
        return $this->hasMany(Option::class); 
    }
}
