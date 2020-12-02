<?php

namespace App\Repositories\Category;

use App\Models\Category;

use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements CategoryInterface
{

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function createCategories($categories, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($categories as $item) {
            if ($item['parent_id'] == $parent_id) {
                $item['level'] = $level;
                $item['children'] = $this->createCategories($categories, $item['id'], $level + 1);
                $result[] = $item;
            }
        }
        return $result;
    }

    public function getCategories()
    {
        try {
            $categories = $this->model->select(DB::raw("categories.*, categories.name as label"))->get();
            $data = $this->createCategories($categories, 0);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    public function deleteCategory($id)
    {
        try {
            $category = $this->model->findOrFail($id);
            $categoryChildren = $this->model->where('parent_id', $id)->get();
            if ($categoryChildren != null) {
                foreach ($categoryChildren as $categories) {
                    $categories->parent_id = $category->parent_id;
                    $categories->update();
                }
            }
            $category->delete();

            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function storeCategory($data)
    {
        DB::beginTransaction();
        try {
            $this->model->create($data);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateCategory($data, $id)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($id);
            $category->update($data);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'success' => true,
        ];
    }
}
