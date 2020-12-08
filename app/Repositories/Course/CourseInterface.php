<?php


namespace App\Repositories\Course;


interface CourseInterface
{
    public function getListCourse($request);
    public function deleteCourse($id);
    public function createCourse(array $data);
    public function updateCourse(array $data, $id);
    public function assignUserToCourse($userId, $courseId);
    public function getCourseById($id);
}
