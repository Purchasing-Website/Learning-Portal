<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classes;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'status',
        'progress',
        'enrolled_at'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
