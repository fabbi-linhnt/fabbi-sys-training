<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleModelTest extends TestCase
{
    public function test_role_belongs_to_many_permissions()
    {
        $this->assertBelongsToMany(Permission::class, 'role_id', 'permission_id', new Role, 'permissions');
    }

    public function test_role_belongs_to()
    {
       $this->assertBelongsTo(User::class, 'user_id', 'id', new Role, 'user');
    }

    protected function assertBelongsToMany($related, $foreignKey, $relatedKey, $model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(BelongsToMany::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignPivotKeyName(), 'Foreign Pivot key is wrong');
        $this->assertEquals($relatedKey, $relation->getRelatedPivotKeyName(), 'Related Pivot key is wrong');
    }

    protected function assertBelongsTo($related, $foreignKey, $ownerKey, $model, $relationName)
    {
       $relation = $model->$relationName();

       $this->assertInstanceOf(BelongsTo::class, $relation, 'Relation is wrong');
       $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
       $this->assertEquals($ownerKey, $relation->getOwnerKeyName(), 'Owner key is wrong');
       $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foregin key is wrong');
    }
}
