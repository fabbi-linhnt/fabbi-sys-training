<?php

namespace App\Repositories\Subject;

use App\Repositories\BaseRepositoryInterface;

interface SubjectInterface extends BaseRepositoryInterface
{
    public function getListSubject($request);
    public function deleteSubject($id);
    public function createSubject($data);
    public function updateSubject($data, $id);
    public function getSubjectById($id);
    public function countTaskCourseById($id);
    public function updateActive($id);
    public function assignSubjectToUserById($userId, $id);
}


