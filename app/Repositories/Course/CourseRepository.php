<?php


namespace App\Repositories\Course;

use App\Enums\ResponseMessage;
use App\Models\Course;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class CourseRepository extends BaseRepository implements CourseInterface
{
    public function __construct(Course $course)
    {
        $this->model = $course;
    }

    public function getCourseById($id)
    {
        try {
            $data = $this->model->findOrFail($id);
            return [
                'data' => $data,
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getListCourse($request)
    {
        $perPage = $request->perPage;
        $listCourse = $this->model;
        if (!empty($request['name'])) {
            $listCourse = $listCourse->where('name', 'LIKE', '%' . $request['name'] . '%');
            return [
                'success' => true,
                'listCourse' => $listCourse->paginate($perPage)
            ];
        }
        return [
            'success' => true,
            'listCourse' => $listCourse->paginate($perPage)
        ];
    }

    public function createCourse($data)
    {
        DB::beginTransaction();
        try {
            $course = $this->model->create($data);
            if ($data['path'] == '') {
                $data['path'] = config('configcourse.images_default');
            }
            $course->image()->create(['path' => $data['path']]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'message' => ResponseMessage::COURSE['ADD_SUCCESS']
        ];
    }

    public function updateCourse($data, $id)
    {
        DB::beginTransaction();
        try {
            $course = $this->model->findOrFail($id);
            $course->update($data);
            if ($data['path'] == '') {
                $data['path'] = config('configcourse.images_default');
            }
            $course->image()->create(['path' => $data['path']]);

            DB::commit();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'message' => ResponseMessage::COURSE['UPDATE_SUCCESS']
        ];
    }

    public function deleteCourse($id)
    {
        try {
            $courseByID = $this->model->findOrFail($id);
            $courseByID->delete();
            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'success' => false
            ];
        }
    }

    public function listCategoryByCourseId($id)
    {
        try {
            $course = $this->model->findOrFail($id);
            $data = $course->categories;
            return [
                'success' => true,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function assignUserToCourse($userId, $courseId)
    {
        DB::beginTransaction();
        try {
            $course = $this->model->findOrFail($courseId);
            $checkUserActive = DB::table('user_course')
                ->where('user_id', $userId)
                ->where('status', config('config.user_course.active'))
                ->count();
            $checkCourseOfUser = DB::table('user_course')
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->count();
            if ($checkUserActive >= config('configcourse.have_active_user')) {
                return [
                    'success' => false,
                    'message' => ResponseMessage::COURSE['ASSIGN_ERROR']
                ];
            } elseif ($checkCourseOfUser >= config('configcourse.finish_course')) {
                return [
                    'success' => false,
                    'message' => ResponseMessage::COURSE['FINISH_COURSE']
                ];
            } else {
                $course->users()->attach($userId, ['status' => config('config.user_course.active')]);

                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'success' => true,
            'message' => ResponseMessage::COURSE['ASSIGN_SUCCESS']
        ];
    }
}
