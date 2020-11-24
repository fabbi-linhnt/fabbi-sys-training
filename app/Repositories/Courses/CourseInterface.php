<?php


namespace App\Repositories\Courses;


interface CourseInterface
{
    public function getListCourse($request);
    public function deleteCourse($id);
    public function createCourse(array $data);
    public function updateCourse(array $data, $id);
}
