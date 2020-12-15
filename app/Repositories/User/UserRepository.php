<?php

namespace App\Repositories\User;

use App\Enums\ResponseMessage;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EquipmentRepository
 * @package App\Repositories\Equipment
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }

    public function addUser(array $data)
    {
        $data['img_path'] = ($data['img_path']) ?? config('configuser.images_default');
        DB::beginTransaction();
        try {
            $user = $this->user->create($data);
            $user->courses()->attach(
                $data['course_id'],
                ['status' => config('configcourse.status_user_inactive')]
            );
            $this->storeCourseSubjectTask($data['course_id'], $user);
            $user->image()->create(['path' => $data['img_path']]);

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
            'message' => ResponseMessage::USER['ADD_SUCCESS']
        ];
    }

    public function getListUser(array $data)
    {
        try {
            $list = $this->model;
            $perPage = $data['perPage'];
            if (array_key_exists('search', $data) && !empty($data['search'])) {
                $input = $data['search'];
                $list->where('name', 'LIKE', "%$input%");
            }

            return [
                'success' => true,
                'listUser' => $list->paginate($perPage)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getUserInfoById($id)
    {
        try {
            $userData = $this->user->findOrFail($id);
            $name = $userData->name;
            $email = $userData->email;
            $phone = $userData->phone;
            $address = $userData->address;
            $numberTask = $userData->tasks()->where('user_id', $id)->count();
            $numberSubject = $userData->subjects()->where('user_id', $id)->count();
            $courseParticipatedInfo = $userData->courses()->where('user_id', $id)->select('course_id')->get()->toArray();
            $courseParticipatedName = array();
            foreach ($courseParticipatedInfo as $value) {
                $courseParticipatedName[] = DB::table('courses')->where('id', $value['course_id'])->value('name');
            }
            $result = [
                "name" => $name,
                "numberSubject" => $numberSubject,
                "numberTask" => $numberTask,
                "courseParticipatedName" => $courseParticipatedName,
                "email" => $email,
                "phone" => $phone,
                "address" => $address
            ];

            return [
                'success' => true,
                'result' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteUserById($id)
    {
        try {
            $userData = $this->user->findOrFail($id);
            $userData->subjects()->detach();
            $userData->courses()->detach();
            $userData->tasks()->detach();
            $userData->delete();

            return [
                'success' => true,
                'message' => ResponseMessage::USER['DELETE_SUCCESS']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateUserById($data, $id)
    {
        $data['img_path'] = ($data['img_path']) ?? config('configuser.images_default');
        DB::beginTransaction();
        try {
            $listCourseId = $data['course_id'];
            $user = $this->user->findOrFail($id);
            $user->update($data);
            $courseOfUser = DB::table('user_course')
                ->where('user_id', $id)
                ->where('status', '=', config('configcourse.status_user_inactive'))
                ->get();
            foreach ($courseOfUser as $courseId) {
                $course = Course::find($courseId->course_id);
                foreach ($course->subjects as $subjectId) {
                    $subject = Subject::find($subjectId->id);
                    foreach ($subject->tasks as $taskId) {
                        $task = Task::find($taskId->id);
                        $task->users()->detach($id);
                    }
                    $subject->users()->detach($id);
                }
                $course->users()->detach($id);
            }
            $existsCourseActive = DB::table('user_course')
                ->where('user_id', $id)
                ->where('status', '=', config('configcourse.status_user_activity'))
                ->first();
            if ($existsCourseActive) {
                if (in_array($existsCourseActive->course_id, $listCourseId)) {
                    unset($listCourseId[array_search($existsCourseActive->course_id, $listCourseId)]);
                }
            }

            $user->courses()->attach(
                $listCourseId,
                ['status' => config('configcourse.status_user_inactive')]
            );
            $this->storeCourseSubjectTask($listCourseId, $user);
            $user->image()->create(['path' => $data['img_path']]);

            DB::commit();
            return [
                'success' => true,
                'message' => ResponseMessage::USER['UPDATE_SUCCESS']
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getListCourseByUserId($id)
    {
        try {
            $course = DB::table('user_course')
                ->join('courses', 'course_id', '=', 'courses.id')
                ->where('user_id', $id)
                ->paginate(config('config.perPage'));

            return [
                'success' => true,
                'result' => $course
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function storeCourseSubjectTask($listCourseId, $user)
    {
        foreach ($listCourseId as $courseId) {
            $course = Course::find($courseId);
            foreach ($course->subjects as $subject) {
                $user->subjects()->attach(
                    $subject->pivot->subject_id, ['status' => config('configsubject.status_user_inactive')]
                );
                $subject = Subject::find($subject->pivot->subject_id);
                foreach ($subject->tasks as $task) {
                    $user->tasks()->attach(
                        $task->pivot->task_id, ['status' => config('configtask.status_user_inactive')]
                    );
                }
            }
        }
    }
}
