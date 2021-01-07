<?php


namespace App\Repositories\Course;

use App\Enums\ResponseMessage;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Task;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
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
        try {
            $listCourse = $this->model;
            if (!empty($request['name'])) {
                $listCourse = $listCourse->where('name', 'LIKE', '%' . $request['name'] . '%');
            }

            return [
                'success' => true,
                'listCourse' => $listCourse->paginate($request['perPage'])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
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
                ->where('status', config('configcourse.status_user_activity'))
                ->count();
            $checkCourseOfUser = DB::table('user_course')
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('status', config('configcourse.status_user_finished'))
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
                DB::table('user_course')
                    ->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->update(['status' => config('configcourse.status_user_activity'), 'updated_at' => Carbon::now()]);

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

    public function getListUserByCourseId($id)
    {
        try {
            $listUser = DB::table('user_course')
                ->join('users', 'user_id', '=', 'users.id')
                ->where('course_id', $id)
                ->paginate(config('config.perPage'));

            return [
                'success' => true,
                'result' => $listUser
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getTimeCourse($userId, $id)
    {
        $timeCourse = 0;
        $result = [];
        $itemCourse = [];
        try {
            $course = Course::find($id);
            $timeUserAssignCourseStart = DB::table('user_course')
                        ->where('user_id', $userId)
                        ->where('course_id', $id)
                        ->value('updated_at');
            $subjectStart = date("Y-m-d", strtotime($timeUserAssignCourseStart));
            foreach($course->subjects as $subject) {
                $timeSubject = 0;
                $subject = Subject::find($subject->pivot->subject_id);
                $taskStart = strftime("%Y-%m-%d", date(strtotime(date("Y-m-d", strtotime($timeUserAssignCourseStart)) . " +$timeCourse day")));
                foreach($subject->tasks as $taskId) {
                    $task = Task::find($taskId->pivot->task_id);
                    $timeSubject += $task->time;
                    $timeCourse += $task->time;
                    $itemTask = [];
                    $itemTask['id'] = $task->id;
                    $itemTask['title'] = $task->name;
                    $itemTask['start'] = $taskStart;
                    $end = date(strtotime(date("Y-m-d", strtotime($taskStart)) . " +$task->time day"));
                    $itemTask['end'] = strftime("%Y-%m-%d", $end);
                    $taskStart = $itemTask['end'];
                    array_push($result, $itemTask);
                }
                $itemSubject = [];
                $itemSubject['title'] = $subject->name;
                $itemSubject['start'] = $subjectStart;
                $end = date(strtotime(date("Y-m-d", strtotime($subjectStart)) . " +$timeSubject day"));
                $itemSubject['end'] = strftime("%Y-%m-%d", $end);
                $subjectStart = $itemSubject['end'];
                $itemSubject['color'] = '#378006';
                array_push($result, $itemSubject);
            }
            $itemCourse['title'] = $course->name;
            $itemCourse['start'] = date("Y-m-d", strtotime($timeUserAssignCourseStart));
            $end = date(strtotime(date("Y-m-d", strtotime($timeUserAssignCourseStart)) . " +$timeCourse day"));
            $itemCourse['end'] = strftime("%Y-%m-%d", $end);
            $itemCourse['color'] = '#ff0000';
            array_push($result, $itemCourse);

            return [
                'success' => true,
                'result' => $result,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
