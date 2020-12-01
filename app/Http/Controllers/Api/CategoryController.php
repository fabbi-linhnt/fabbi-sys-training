<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoryController extends ApiBaseController
{
    private $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $categories =  $this->categoryRepository->categories();
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($categories['data']);
    }

    public function destroy($id)
    {
        $categories =  $this->categoryRepository->deleteCategory($id);
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }
}
