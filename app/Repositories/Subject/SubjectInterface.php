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
    public function countTaskCourseSubjectById($id);
    public function ListCourseBySubjectId($id);
    public function updateActive($id);
}


