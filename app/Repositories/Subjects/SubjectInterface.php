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
    public function countTaskCourseSubjectById($id);
    public function listCourses();
    public function ListCourseBySubjetID($id);
    public function updateActive($id);
    public function getAllSubject();
}


