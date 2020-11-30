<?php

namespace App\Repositories\Subjects;

use App\Repositories\BaseRepositoryInterface;

interface SubjectInterface extends BaseRepositoryInterface
{
    public function getListSubject($request);
    public function deleteSubject($id);
    public function createSubject($data);
    public function updateSubject($data, $id);
    public function getSubjectById($id);
    public function countTaskCourseById($id);
    public function listCourseBySubjectId($id);
    public function updateActive($id);
}


