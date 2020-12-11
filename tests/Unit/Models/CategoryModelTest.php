<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Course;

class CategoryModelTest extends TestCase
{
    public function test_category_has_many_courses()
    {
        $this->assertHasMany(Course::class, 'category_id', new Category, 'courses');
    }

    public function test_category_has_many_categories()
    {
        $this->assertHasMany(Category::class, 'parent_id', new Category, 'categories');
    }

    protected function assertHasMany($related, $foreignKey, $model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(HasMany::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foreign key is wrong');
    }
}
