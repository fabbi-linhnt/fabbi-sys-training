<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PermissionModelTest extends TestCase
{
    public function test_permission_belongs_to_many_roles()
    {
        $this->assertBelongsToMany(Role::class, 'permission_id', 'role_id', new Permission, 'roles');
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
