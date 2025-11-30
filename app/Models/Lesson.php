<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;

class Lesson extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'sequence',
        'content_type',
        'source_url',
        'duration',
        'knowledge_check_id',
        'pass_score',
        'class_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function quiz() { 
        return $this->belongsTo(Quiz::class, 'knowledge_check_id'); 
    }
}
