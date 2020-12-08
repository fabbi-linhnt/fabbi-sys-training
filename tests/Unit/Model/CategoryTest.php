<?php

namespace Tests\Unit\Model;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_category_has_many_course()
    {
        $category = new Category();
        $relation = $category->courses();
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('courses.category_id', $relation->getQualifiedForeignKeyName());
    }
}
