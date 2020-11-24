<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repositories\Courses\CourseInterface;
use Illuminate\Http\Request;

class CourseController extends ApiBaseController
{
    protected $CourseRepo;

    public function __construct(CourseInterface $courseRepository)
    {
        $this->CourseRepo = $courseRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/courses?name={name}&perPage={perPage}&page={page}",
     *      description="Get courses",
     *      tags={"Course"},
     *      @OA\Parameter(in="query",name="name",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="perPage",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="page",@OA\Schema(type="string")),
     *      @OA\Response(
     *          response="200",
     *          description="response courses success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": {
     *                          "id": 1,
     *                          "name": "sr7Mlx8itY",
     *                          "description": "Wd00fJflFQ",
     *                          "is_active": 0,
     *                          "category_id": 4,
     *                          "created_at": null,
     *                          "updated_at": null
     *                       },
     *                       "current_page":1,
     *                       "first_page_url": "http://localhost:2222/api/courses?page=1",
     *                       "from": 1,
     *                       "last_page": 1,
     *                       "last_page_url": "http://localhost:2222/api/courses?page=1",
     *                       "next_page_url": null,
     *                       "path": "http://localhost:2222/api/courses",
     *                       "per_page": 15,
     *                       "prev_page_url": null,
     *                       "to": 10,
     *                       "total": 10
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $courseList = $this->CourseRepo->getListCourse($request);
        if (!$courseList['success']) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($courseList['listCourse']);
    }

    /**
     * @OA\Post(
     *      path="/api/courses",
     *      description="Store course",
     *      tags={"Course"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="category_id", type="integer"),
     *              @OA\Property(property="is_active", type="boolean"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Store course success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
        $addCourse = $this->CourseRepo->createCourse($fillable);
        if (!$addCourse) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($addCourse['data']);
    }

    /**
     * @OA\Post(
     *      path="/api/courses/{id}",
     *      description="Update course",
     *      tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         description="course id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="category_id", type="integer"),
     *              @OA\Property(property="is_active", type="boolean"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Store course success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
        $editCourse = $this->CourseRepo->updateCourse($fillable, $id);
        if (!$editCourse) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($editCourse['success']);
    }

    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
     *     description="delete course",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         description="course id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="delete course success"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid course id"
     *     ),
     * )
     */
    public function destroy($id)
    {
        return $this->CourseRepo->deleteCourse($id);
    }

    /**
     * @OA\get(
     *     path="/api/courses/{id}",
     *     description="show course detail",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         description="course id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="response course success",
     *         @OA\JsonContent(
     *              type="object",
     *                  examples={
     *                      "data": {
     *                      "id": 1,
     *                      "name": "sr7Mlx8itY",
     *                      "description": "Wd00fJflFQ",
     *                      "is_active": 0,
     *                      "category_id": 4,
     *                      "created_at": "2020-12-12",
     *                      "updated_at": "2020-12-12"
     *            }
     *         }
     *     ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid course id"
     *     ),
     * )
     */
    public function show($id)
    {
        $course = $this->CourseRepo->getCourseById($id);
        if (!$course) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($course['data']);
    }

    /**
     * @OA\get(
     *     path="/api/courses/category/{id}",
     *     description="show category of course",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         description="course id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="response courses success",
     *         @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "id": 4,
     *                      "name": "qrmNsYcdFg",
     *                      "parent_id": 0,
     *                      "created_at": "2020-12-12",
     *                      "updated_at": "2020-12-12"
     *                  }
     *              }
     *          ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid course id"
     *     ),
     * )
     */
    public function listCategoryByCourseId($id)
    {
        $category = $this->CourseRepo->listCategoryByCourseId($id);
        if (!$category) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($category['data']);
    }
}
