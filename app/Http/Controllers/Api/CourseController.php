<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Courses\CourseStoreRequest;
use App\Http\Requests\Courses\CourseUpdateRequest;
use App\Repositories\Course\CourseInterface;
use Illuminate\Http\Request;

class CourseController extends ApiBaseController
{
    protected $courseRepository;

    public function __construct(CourseInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index(Request $request)
    {
        $courseList = $this->courseRepository->getListCourse($request);
        if (!$courseList['success']) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($courseList['listCourse']);
    }

    public function store(CourseStoreRequest $request)
    {
        $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
        $addCourse = $this->courseRepository->createCourse($fillable);
        if (!$addCourse) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($addCourse['data']);
    }

    public function update(CourseUpdateRequest $request, $id)
    {
        $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
        $editCourse = $this->courseRepository->updateCourse($fillable, $id);
        if (!$editCourse) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($editCourse['success']);
    }

    public function destroy($id)
    {
        return $this->courseRepository->deleteCourse($id);
    }

    public function show($id)
    {
        $course = $this->courseRepository->getCourseById($id);
        if (!$course) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($course['data']);
    }

    public function getCategory()
    {
        $category = $this->courseRepository->getCategory();
        if (!$category) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($category['data']);
    }

    public function listCategoryByCourseId($id)
    {
        $category = $this->courseRepository->listCategoryByCourseId($id);
        if (!$category) {
            return $this->sendError(500, 'ERROR', 500);
        }

        return $this->sendSuccess($category['data']);
    }
}
