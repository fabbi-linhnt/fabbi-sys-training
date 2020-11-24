<?php

namespace App\Repositories\Categories;

use App\Repositories\BaseRepositoryInterface;

interface CategoryInterface extends BaseRepositoryInterface
{
    public function categories();
    public function deleteCategory($id);
    public function createCategories($categories, $parent_id = 0, $level = 0);
}
