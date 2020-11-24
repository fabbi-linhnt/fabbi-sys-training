<?php

namespace App\Repositories\Task;

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

    public function getSubjectOfTask($id)
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
}
