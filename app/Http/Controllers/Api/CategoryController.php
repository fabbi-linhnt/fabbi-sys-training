<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
use App\Enums\ResponseStatus;
use App\Enums\ResponseStatusCode;
use App\Http\Requests\Categories\CategoriesStoreRequest;
use App\Http\Requests\Categories\CategoriesUpdateRequest;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoryController extends ApiBaseController
{
    private $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getCategories();
        if (!$categories['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $categories['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($categories['data']);
    }


    public function store(CategoriesStoreRequest $request)
    {
        $data = $request->only(['parent_id', 'name']);
        $category = $this->categoryRepository->storeCategory($data);
        if (!$category['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $category['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::CATEGORY['add_success']);
    }

    public function update(CategoriesUpdateRequest $request, $id)
    {
        $data = $request->only(['parent_id', 'name']);
        $category = $this->categoryRepository->updateCategory($data, $id);
        if (!$category['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $category['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::CATEGORY['update_success']);
    }

    public function destroy($id)
    {
        $categories = $this->categoryRepository->deleteCategory($id);
        if (!$categories['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $categories['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::CATEGORY['delete_success']);
    }
}
