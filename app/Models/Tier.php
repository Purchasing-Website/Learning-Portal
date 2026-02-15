<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Tier extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // Tier.php
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
