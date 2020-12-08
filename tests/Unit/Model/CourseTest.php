<?php

namespace Tests\Unit\Model;

use App\Models\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Tests\TestCase;

class CourseTest extends TestCase
{
    public function test_course_belongs_to_category()
    {
        $course = new Course();
        $relation = $course->category();
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('category_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
    }

    public function test_course_morph_one_image()
    {
        $course = new Course();
        $relation = $course->image();
        $this->assertInstanceOf(MorphOne::class, $relation);
        $this->assertEquals('imageable_id', $relation->getForeignKeyName());
        $this->assertEquals('App\Models\Course', $relation->getMorphClass());
    }

    public function test_course_belongs_to_many_users()
    {
        $course = new Course();
        $relation = $course->users();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('user_course.course_id', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('user_course.user_id', $relation->getQualifiedRelatedPivotKeyName());
    }

    function test_course_belongs_to_many_subjects()
    {
        $course = new Course();
        $relation = $course->subjects();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('course_subject.course_id', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('course_subject.subject_id', $relation->getQualifiedRelatedPivotKeyName());
    }
}
