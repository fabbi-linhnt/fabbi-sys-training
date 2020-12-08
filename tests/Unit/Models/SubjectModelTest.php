<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjectModelTest extends TestCase
{
    public function test_subject_belongs_to_many_users()
    {
        $this->assertBelongsToMany(User::class, 'subject_id', 'user_id', new Subject, 'users');
    }

    public function test_subject_belongs_to_many_tasks()
    {
        $this->assertBelongsToMany(Task::class, 'subject_id', 'task_id', new Subject, 'tasks');
    }

    public function test_subject_belongs_to_many_courses()
    {
        $this->assertBelongsToMany(Course::class, 'subject_id', 'course_id', new Subject, 'courses');
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
