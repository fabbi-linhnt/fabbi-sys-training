<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
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
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::COURSE['GET_LIST_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
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
        $course = $this->courseRepository->deleteCourse($id);
        if (!$course['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::COURSE['DELETE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::COURSE['DELETE_SUCCESS']);
    }

    public function show($id)
    {
        $course = $this->courseRepository->getCourseById($id);
        if (!$course['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::COURSE['SHOW_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($course['data']);
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
