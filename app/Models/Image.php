<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    function users()
    {
        return $this->belongsTo(User::class, 'id', 'imgable_id');
    }

    function images()
    {
        return $this->belongsTo(Course::class, 'id', 'imgable_id');
    }
}
