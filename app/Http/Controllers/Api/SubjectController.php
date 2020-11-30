<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Subjects\SubjectInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(SubjectInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $subjects = $this->repository->getListSubject($request);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->repository->createSubject($data);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("ADD SUBJECT SUCCESS");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subjects = $this->repository->getSubjectbyId($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->repository->updateSubject($data, $id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $subjects = $this->repository->deleteSubject($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }

    public function countTaskCourseById($id)
    {
        $subjects = $this->repository->countTaskCourseById($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function listCourseBySubjectId($id)
    {
        $subjects = $this->repository->listCourseBySubjectId($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    public function edit($id)
    {
        $subject = $this->repository->updateActive($id);
        if (!$subject['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
    }
}

