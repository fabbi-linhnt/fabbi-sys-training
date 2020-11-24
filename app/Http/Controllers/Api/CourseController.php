<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repositories\Courses\CourseInterface;
use Illuminate\Http\Request;

class CourseController extends ApiBaseController
{
  protected $CourseRepo;

  public function __construct(
    CourseInterface $courseRepository,
    Course $course)
  {
    $this->CourseRepo = $courseRepository;
  }
  public function index(Request $request)
  {
    $courseList = $this->CourseRepo->getListCourse($request);
    if (!$courseList['success']) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($courseList['listCourse']);
  }

  public function store(Request $request)
  {
    $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
    $addCourse = $this->CourseRepo->createCourse($fillable);
    if (!$addCourse) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($addCourse['data']);
  }

  public function update(Request $request, $id)
  {
    $fillable = $request->only(['name', 'description', 'is_active', 'category_id']);
    $editCourse = $this->CourseRepo->updateCourse($fillable, $id);
    if (!$editCourse) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($editCourse['success']);
  }

  public function destroy($id)
  {
    return $this->CourseRepo->deleteCourse($id);
  }
  public function show($id)
  {
    $course = $this->CourseRepo->getCourseById($id);
    if (!$course) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($course['data']);
  }

  public function getCategory()
  {
    $category = $this->CourseRepo->getCategory();
    if (!$category) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($category['data']);
  }
  public  function listCategoryByCourseId($id){
    $category = $this->CourseRepo->listCategoryByCourseId($id);
    if (!$category) {
      return $this->sendError(500, 'ERROR', 500);
    }

    return $this->sendSuccess($category['data']);
  }
}
