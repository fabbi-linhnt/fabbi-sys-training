<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    function users()
    {
        return $this->belongsToMany(User::class, 'user_subject');
    }

    function tasks()
    {
        return $this->belongsToMany(Task::class, 'subject_task');
    }

    function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject');
    }
}
