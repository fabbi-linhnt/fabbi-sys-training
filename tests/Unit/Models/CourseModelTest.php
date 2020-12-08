<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Tests\TestCase;
use App\Models\Course;
use App\Models\Image;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseModelTest extends TestCase
{
    public function test_course_belongs_to_category()
    {
        $this->assertBelongsTo(Category::class, 'category_id', 'id', new Course, 'categories');
    }

    public function test_course_morphone_image()
    {
        $this->assertMorphOneImage(Image::class, new Course, 'imageable', 'image');
    }

    public function test_course_belongs_to_many_users()
    {
        $this->assertBelongsToMany(User::class, 'course_id', 'user_id', new Course, 'users');
    }

    public function test_course_belongs_to_many_subjects()
    {
        $this->assertBelongsToMany(Subject::class, 'course_id', 'subject_id', new Course, 'subjects');
    }

    protected function assertMorphOneImage($related, $model, $name, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(MorphOne::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($name . '_type', $relation->getMorphType(), 'MorphType relation is wrong');
        $this->assertEquals($name . '_id', $relation->getForeignKeyName(), 'Foreign key is wrong');
    }

    protected function assertBelongsTo($related, $foreignKey, $ownerKey, $model, $relationName)
    {
       $relation = $model->$relationName();

       $this->assertInstanceOf(BelongsTo::class, $relation, 'Relation is wrong');
       $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
       $this->assertEquals($ownerKey, $relation->getOwnerKeyName(), 'Owner key is wrong');
       $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foregin key is wrong');
    }

    protected function assertBelongsToMany($related, $foreignKey, $relatedKey, $model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(BelongsToMany::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignPivotKeyName(), 'Foreign Pivot key is wrong');
        $this->assertEquals($relatedKey, $relation->getRelatedPivotKeyName(), 'Related Pivot key is wrong');
    }
}
