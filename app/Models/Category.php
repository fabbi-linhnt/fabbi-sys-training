<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name'
    ];

    protected $table = 'categories';

    function courses()
    {
        return $this->hasMany(Course::class);
    }
}
