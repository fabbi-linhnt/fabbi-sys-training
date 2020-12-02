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
        $tasks = $this->model;
        if (!empty($request['name'])) {
            return [
                'success' => true,
                'data' => $tasks->where('name', 'like', '%' . $request['name'] . '%')->paginate($request['perPage'])
            ];
        }
        return [
            'success' => true,
            'data' => $tasks->paginate($request['perPage'])
        ];
    }

    public function createTask($data)
    {
        try {
            $task = $this->model->create($data['task']);
            $task->subjects()->attach($data['subject_id'], ['status' => config('config.subject_task.status_default')]);
            $task->users()->attach($data['user_id'], ['status' => config('config.user_task.late')]);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true
        ];
    }

    public function showTask($id)
    {
        try {
            $task = $this->model->findOrFail($id);
            return [
                'success' => true,
                'data' => $task
            ];
        } catch (Exception $e) {
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
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateTask($data, $id)
    {
        try {
            $task = $this->model->findOrFail($id);
            $task->update($data['task']);
            $task->subjects()->detach();
            $task->subjects()->attach($data['subject_id'], ['status' => config('config.subject_task.status_default')]);
            $task->users()->detach();
            $task->users()->attach($data['user_id'], ['status' => config('config.user_task.late')]);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getSubjectsByTaskId($id)
    {
        try {
            $task = $this->model->findOrFail($id)->subjects;
            return [
                'success' => true,
                'data' => $task
            ];
        } catch (Exception $e) {
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
                ->select('user_task.*', 'users.name as username', 'tasks.deadline', 'tasks.name', 'user_task.date_submit')
                ->where('task_id', $id)
                ->get();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => $data
        ];
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
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true
        ];
    }

    public function getUsersByTaskId($id)
    {
        try {
            $data = $this->model->findOrFail($id)->users;
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => $data
        ];
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
            }
            else {
                $countTaskOfUser = DB::table('user_task')
                    ->where('user_id', $userId)
                    ->where('task_id', $id)
                    ->count();
                if ($countTaskOfUser >= 1)
                {
                    DB::table('user_task')
                        ->where('user_id', $userId)
                        ->where('task_id', $id)
                        ->update(['status' => config('configtask.status_user_activity')]);

                    return [
                        'success' => true,
                        'message' => ResponseMessage::TASK['ASSIGN_SUCCESS']
                    ];
                }
                else {
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
                    }
                    else {
                        return [
                            'success' => false,
                            'message' => ResponseMessage::TASK['ASSIGN_ERROR']
                        ];
                    }
                }
            }
        }
        catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
