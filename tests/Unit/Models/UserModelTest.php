<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Task;
use App\Models\Image;

class UserModelTest extends TestCase
{
    public function test_user_has_many_roles()
    {
        $this->assertHasMany(Role::class, 'user_id', new User, 'roles');
    }

    public function test_user_morph_one_image()
    {
        $this->assertMorphOneImage(Image::class, new User, 'imageable', 'image');
    }

    public function test_user_belongs_to_many_subjects()
    {
        $this->assertBelongsToMany(Subject::class, 'user_id', 'subject_id', new User, 'subjects');
    }

    public function test_user_belongs_to_many_courses()
    {
        $this->assertBelongsToMany(Course::class, 'user_id', 'course_id', new User, 'courses');
    }

    public function test_user_belongs_to_many_tasks()
    {
        $this->assertBelongsToMany(Task::class, 'user_id', 'task_id', new User, 'tasks');
    }

    protected function assertHasMany($related, $foreignKey, $model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(HasMany::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foreign key is wrong');
    }

    protected function assertMorphOneImage($related, $model, $name, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(MorphOne::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($name . '_type', $relation->getMorphType(), 'MorphType is wrong');
        $this->assertEquals($name . '_id', $relation->getForeignKeyName(), 'Foreign name is wrong');
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
