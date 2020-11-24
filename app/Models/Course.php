<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'category_id'];


    public function getFillable()
    {
        return $this->fillable;
    }

    function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    function course()
    {
        return $this->hasOne(Image::class, 'imgable_id');
    }

    function users()
    {
        return $this->belongsToMany(User::class, 'user_course', 'course_id', 'user_id');
    }

    function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subject', 'course_id', 'subject_id');
    }
}
