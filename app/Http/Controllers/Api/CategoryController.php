<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Categories\CategoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends ApiBaseController
{
    public function __construct(CategoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     description="Returns list categories",
     *     @OA\Response(
     *          response="200",
     *          description="response categories success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "id": 1,
     *                       "name": "RTtBs1ara1",
     *                       "parent_id": 0,
     *                       "created_at": null,
     *                       "updated_at": null,
     *                       "level": 0,
     *                       "children": {
     *                           {
     *                           "id": 5,
     *                           "name": "Evb05fVF9K",
     *                           "parent_id": 1,
     *                           "created_at": null,
     *                           "updated_at": null,
     *                           "level": 1,
     *                           "children": {}
     *                           },
     *                          {
     *                           "id": 6,
     *                           "name": "Evb05fVF9K",
     *                           "parent_id": 1,
     *                           "created_at": null,
     *                           "updated_at": null,
     *                           "level": 1,
     *                           "children": {}
     *                           },
     *                      },
     *                  },
     *             },
     *          ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $categories = $this->repository->categories();
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($categories['data']);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     description="delete category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         description="category id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="delete category success"
     *     ),
     * )
     */
    public function destroy($id)
    {
        $categories = $this->repository->deleteCategory($id);
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }
}
