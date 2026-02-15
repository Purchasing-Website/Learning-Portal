<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tier;
use App\Models\Classes;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
        'created_by',
        'updated_by',
        'program_id'
    ];

    // Relationship to Program
    // public function tier()
    // {
    //     return $this->belongsTo(Tier::class);    
    // }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_course', 'course_id', 'class_id')
                    ->withPivot('sequence_order', 'is_active', 'created_by', 'updated_by')
                    ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
