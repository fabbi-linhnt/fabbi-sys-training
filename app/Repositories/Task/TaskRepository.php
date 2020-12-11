<?php

namespace App\Repositories\Task;

use App\Enums\ResponseMessage;
use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getTasks($request)
    {
        try {
            $tasks = $this->model;
            if (!empty($request['name'])) {
                $tasks = $tasks->where('name', 'like', '%' . $request['name'] . '%');
            }

            return [
                'success' => true,
                'data' => $tasks->paginate($request['perPage'])
            ];
        } catch (\Exception $e) {
            return [
                'success' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function createTask($data)
    {
        try {
            $task = $this->model->create($data['task']);
            $task->subjects()->attach($data['subject_id'], ['status' => config('config.subject_task.status_default')]);

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

    public function showTask($id)
    {
        try {
            $task = $this->model->findOrFail($id);

            return [
                'success' => true,
                'data' => $task
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteTask($id)
    {
        try {
            $task = $this->model->findOrFail($id);
            $task->users()->detach();
            $task->subjects()->detach();
            $task->delete();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateTask($data, $id)
    {
        try {
            $task = $this->model->findOrFail($id);
            $task->update($data['task']);
            $task->subjects()->detach();
            $task->subjects()->attach($data['subject_id'], ['status' => config('config.subject_task.status_default')]);

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSubjectsByTaskId($id)
    {
        try {
            $task = $this->model->findOrFail($id)->subjects;

            return [
                'success' => true,
                'data' => $task
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getUserTask($id)
    {
        try {
            $data = DB::table('user_task')
                ->join('users', 'user_task.user_id', '=', 'users.id')
                ->join('tasks', 'user_task.task_id', '=', 'tasks.id')
                ->select('user_task.*', 'users.name as username', 'tasks.time', 'tasks.name', 'user_task.date_submit')
                ->where('task_id', $id)
                ->get();

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateComment($data, $id)
    {
        try {
            $status =
                $data['status'] == config('config.user_task.on_time')
                    ? config('config.user_task.on_time')
                    : config('config.user_task.late');
            DB::table('user_task')
                ->where('id', $id)
                ->update(['comment' => $data['comment'], 'status' => $status]);

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

    public function getUsersByTaskId($id)
    {
        try {
            $data = $this->model->findOrFail($id)->users;

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function assignUserToTaskById($userId, $id)
    {
        try {
            $checkUserActive = DB::table('user_task')
                ->where('user_id', $userId)
                ->where('status', config('configtask.status_user_activity'))
                ->count();
            if ($checkUserActive >= 1) {
                return [
                    'success' => false,
                    'message' => ResponseMessage::TASK['USER_ACTIVATING']
                ];
            } else {
                $countTaskOfUser = DB::table('user_task')
                    ->where('user_id', $userId)
                    ->where('task_id', $id)
                    ->count();
                if ($countTaskOfUser >= 1) {
                    DB::table('user_task')
                        ->where('user_id', $userId)
                        ->where('task_id', $id)
                        ->update(['status' => config('configtask.status_user_activity')]);

                    return [
                        'success' => true,
                        'message' => ResponseMessage::TASK['ASSIGN_SUCCESS']
                    ];
                } else {
                    $countSubjectOfUser = 0;
                    $task = $this->model->findOrFail($id);
                    $subjectId = $task->subjects;
                    foreach ($subjectId as $subject) {
                        $checkUserSubject = DB::table('user_subject')
                            ->where('user_id', $userId)
                            ->where('subject_id', $subject->id)
                            ->where('status', config('configtask.status_user_activity'))
                            ->count();
                        if ($checkUserSubject >= 1) $countSubjectOfUser++;
                    }
                    if ($countSubjectOfUser >= 1) {
                        $task->users()->attach($userId, ['status' => config('configtask.status_user_activity')]);

                        return [
                            'success' => true,
                            'message' => ResponseMessage::TASK['ASSIGN_SUCCESS']
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => ResponseMessage::TASK['ASSIGN_ERROR']
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getListUserByTaskId($id)
    {
        try {
            $listUser = DB::table('user_task')
                ->join('users', 'user_id', '=', 'users.id')
                ->where('task_id', $id)
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

    public function getListSubjectByTaskId($id)
    {
        try {
            $listSubject = DB::table('subject_task')
                ->join('subjects', 'subject_id', '=', 'subjects.id')
                ->where('task_id', $id)
                ->paginate(config('config.perPage'));

            return [
                'success' => true,
                'result' => $listSubject
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
