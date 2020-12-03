<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseStatus;
use App\Enums\ResponseStatusCode;
use App\Enums\ResponseMessage;
use App\Http\Requests\Subjects\SubjectStoreRequest;
use App\Http\Requests\Subjects\SubjectUpdateRequest;
use App\Models\Subject;
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
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function store(SubjectStoreRequest $request)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->subjectRepository->createSubject($data);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("ADD SUBJECT SUCCESS");
    }

    public function show($id)
    {
        $subjects = $this->subjectRepository->getSubjectbyId($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function update(SubjectUpdateRequest $request, $id)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->subjectRepository->updateSubject($data, $id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
    }

    public function destroy($id)
    {
        //
        $subjects = $this->subjectRepository->deleteSubject($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }

    public function countTaskCourseById($id)
    {
        $subjects = $this->subjectRepository->countTaskCourseById($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function updateActive($id)
    {
        $subject = $this->subjectRepository->updateActive($id);
        if (!$subject['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
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
}
