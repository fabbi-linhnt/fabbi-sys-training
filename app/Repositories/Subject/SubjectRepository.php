<?php

namespace App\Repositories\Subject;

use App\Enums\ResponseMessage;
use App\Models\Subject;
use App\Repositories\BaseRepository;
use App\Repositories\Subject\SubjectInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class EquipmentRepository
 * @package App\Repositories\Equipment
 */
class SubjectRepository extends BaseRepository implements SubjectInterface
{

    public function __construct(Subject $subject)
    {
        $this->model = $subject;
    }

    public function getListSubject($request)
    {
        try {
            $subjects = DB::table('subjects')
                ->join('subject_task', 'subjects.id', '=', 'subject_task.subject_id')
                ->join('tasks', 'subject_task.task_id', '=', 'tasks.id')
                ->select('subjects.*', DB::raw('SUM(tasks.time) as sum_time'))
                ->groupBy('subjects.id');

            if (!empty($request['name'])) {
                $subjects->where('subjects.name', 'like', '%' . $request['name'] . '%');
            }

            return [
                'success' => true,
                'data' => $subjects->paginate($request['perPage'])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteSubject($id)
    {
        try {
            $subject = $this->model->find($id);
            $subject->users()->detach();
            $subject->tasks()->detach();
            $subject->courses()->detach();
            $subject->delete();

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

    public function createSubject($data)
    {
        try {
            $subject = $this->model->create($data);
            $course_id = $data['course_id'];
            $subject->courses()->attach($course_id, ['status' => 'Create']);

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

    public function getSubjectById($id)
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

    public function countTaskCourseById($id)
    {
        try {
            $data = $this->model->findOrFail($id);
            $task = $data->tasks()->count('task_id');
            $course = $data->courses()->count('course_id');
            $user = $data->users()->count('user_id');
            $array = [$task, $course, $user];

            return [
                'data' => $array,
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateSubject($data, $id)
    {
        try {
            $subject = $this->model->findOrFail($id);
            $subject->update($data);
            $courseId = $data['course_id'];
            $subject->courses()->detach();
            $subject->courses()->attach($courseId, ['status' => config('configsubject.status_user_activity')]);

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

    public function updateActive($id)
    {
        try {
            $subject = $this->model->findOrFail($id);
            $subject->is_active = !$subject->is_active;
            $subject->update();

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

    public function assignSubjectToUserById($userId, $id)
    {
        try {
            $checkUserIsActive = DB::table('user_subject')
                ->where('user_id', $userId)
                ->where('status', config('configsubject.status_user_activity'))
                ->count();
            if ($checkUserIsActive >= 1) {
                return [
                    'success' => false,
                    'message' => ResponseMessage::SUBJECT['USER_ACTIVATING']
                ];
            } else {
                $checkUserJoinSubject = DB::table('user_subject')
                    ->where('user_id', $userId)
                    ->where('subject_id', $id)
                    ->count();
                if ($checkUserJoinSubject >= 1) {
                    DB::table('user_subject')
                        ->where('user_id', $userId)
                        ->where('subject_id', $id)
                        ->update(['status' => config('configsubject.status_user_activity')]);

                    return [
                        'success' => true,
                        'message' => ResponseMessage::SUBJECT['ASSIGN_SUCCESS']
                    ];
                } else {
                    $count = 0;
                    $subject = $this->model->findOrFail($id);
                    $courseId = $subject->courses;
                    foreach ($courseId as $course) {
                        $checkUserCourse = DB::table('user_course')
                            ->where('user_id', $userId)
                            ->where('course_id', $course->id)
                            ->where('status', config('configsubject.status_user_activity'))
                            ->count();
                        if ($checkUserCourse >= 1) $count++;
                    }
                    if ($count >= 1) {
                        $subject->users()->attach($userId, ['status' => config('configsubject.status_user_activity')]);

                        return [
                            'success' => true,
                            'message' => ResponseMessage::SUBJECT['ASSIGN_SUCCESS']
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => ResponseMessage::SUBJECT['ASSIGN_ERROR']
                    ];
                }
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
