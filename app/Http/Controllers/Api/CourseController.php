<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseStatus;
use App\Enums\ResponseStatusCode;
use App\Http\Requests\Courses\CourseStoreRequest;
use App\Http\Requests\Courses\CourseUpdateRequest;
use App\Repositories\Course\CourseInterface;
use Illuminate\Http\Request;

class CourseController extends ApiBaseController
{
    private $courseRepository;

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
        $data = $request->only(['name', 'description', 'is_active', 'category_id', 'path']);
        $course = $this->courseRepository->createCourse($data);
        if (!$course['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $course['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($course['message']);
    }

    public function update(CourseUpdateRequest $request, $id)
    {
        $data = $request->only(['name', 'description', 'is_active', 'category_id', 'path']);
        $course = $this->courseRepository->updateCourse($data, $id);
        if (!$course['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $course['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($course['message']);
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

    public function assignUserToCourse(Request $request, $id)
    {
        $userId = $request->only('userId');
        $course = $this->courseRepository->assignUserToCourse($userId, $id);
        if (!$course['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $course['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($course['message']);
    }
}
