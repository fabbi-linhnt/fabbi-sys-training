<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;

interface CategoryInterface extends BaseRepositoryInterface
{
    public function getCategories();
    public function deleteCategory($id);
    public function createCategories($categories, $parent_id = 0, $level = 0);
    public function storeCategory($data);
    public function updateCategory($data, $id);
}
