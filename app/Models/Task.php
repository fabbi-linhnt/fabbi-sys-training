<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'content',
        'is_active',
        'deadline',
    ];

    protected $table = 'tasks';

    function users()
    {
        return $this->belongsToMany(User::class, 'user_task');
    }

    function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_task');
    }
}
