<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseStatus;
use App\Enums\ResponseStatusCode;
use App\Enums\ResponseMessage;
use App\Http\Requests\Subjects\SubjectStoreRequest;
use App\Http\Requests\Subjects\SubjectUpdateRequest;
use App\Repositories\Subject\SubjectInterface;
use Illuminate\Http\Request;

class SubjectController extends ApiBaseController
{
    private $subjectRepository;

    public function __construct(SubjectInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function index(Request $request)
    {
        $subjects = $this->subjectRepository->getListSubject($request);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['GET_LIST_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function store(SubjectStoreRequest $request)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->subjectRepository->createSubject($data);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['ADD_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::SUBJECT['ADD_SUCCESS']);
    }

    public function show($id)
    {
        $subjects = $this->subjectRepository->getSubjectbyId($id);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['SHOW_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function update(SubjectUpdateRequest $request, $id)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->subjectRepository->updateSubject($data, $id);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['UPDATE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::SUBJECT['UPDATE_SUCCESS']);
    }

    public function destroy($id)
    {
        $subjects = $this->subjectRepository->deleteSubject($id);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['DELETE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::SUBJECT['DELETE_SUCCESS']);
    }

    public function countTaskCourseById($id)
    {
        $subjects = $this->subjectRepository->countTaskCourseById($id);
        if (!$subjects['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['COUNT_TASK_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function updateActive($id)
    {
        $subject = $this->subjectRepository->updateActive($id);
        if (!$subject['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::SUBJECT['UPDATE_ACTIVE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::SUBJECT['UPDATE_ACTIVE_SUCCESS']);
    }

    public function assignUserToSubject(Request $request, $id)
    {
        $userId = $request->only('user_id');
        $subject = $this->subjectRepository->assignSubjectToUserById($userId, $id);
        if (!$subject['success']) {
           return $this->sendError(
               ResponseStatusCode::INTERNAL_SERVER_ERROR,
               $subject['message'],
               ResponseStatus::STATUS_ERROR
           );
        }

        return $this->sendSuccess($subject['message']);
    }

    public function getListUserBySubjectId($id)
    {
        $data = $this->subjectRepository->getListUserBySubjectId($id);
        if (!$data['success']) {
            return $this->sendError(
               ResponseStatusCode::INTERNAL_SERVER_ERROR,
               ResponseMessage::SUBJECT['GET_LIST_USER_ERROR'],
               ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function getListCourseBySubjectId($id)
    {
        $data = $this->subjectRepository->getListCourseBySubjectId($id);
        if (!$data['success']) {
            return $this->sendError(
               ResponseStatusCode::INTERNAL_SERVER_ERROR,
               ResponseMessage::SUBJECT['GET_LIST_USER_ERROR'],
               ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }
}
