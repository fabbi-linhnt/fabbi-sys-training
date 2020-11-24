<?php

namespace App\Repositories\Categories;

use App\Models\Category;

use App\Repositories\BaseRepository;
use App\Repositories\Categories\CategoryInterface;

/**
 * Class EquipmentRepository
 * @package App\Repositories\Equipment
 */
class CategoryRepo extends BaseRepository implements CategoryInterface
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

  public function categories()
  {
    $categories =  $this->model->all();
    $data =  $this->createCategories($categories, 0);
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
}
