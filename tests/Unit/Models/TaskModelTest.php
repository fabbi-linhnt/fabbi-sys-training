<?php

namespace Tests\Unit\Models;

use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaskModelTest extends TestCase
{
    public function test_task_belongs_to_many_users()
    {
       $this->assertBelongsToMany(User::class, 'task_id', 'user_id', new Task, 'users');
    }

    public function test_task_belongs_to_many_subjects()
    {
       $this->assertBelongsToMany(Subject::class, 'task_id', 'subject_id', new Task, 'subjects');
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
