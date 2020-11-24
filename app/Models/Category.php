<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    function Courses(){
        return $this->hasMany(Course::class);
    }
    function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
