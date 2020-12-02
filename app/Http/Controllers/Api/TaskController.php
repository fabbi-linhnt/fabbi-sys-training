<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
use App\Enums\ResponseStatus;
use App\Enums\ResponseStatusCode;
use App\Http\Requests\Tasks\TasksStoreRequest;
use App\Http\Requests\Tasks\TasksUpdateRequest;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends ApiBaseController
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskRepository->getTasks($request);
        if (!$tasks['success']) {
            return $this->sendError(500, "ERROR", "500");
        }
        return $this->sendSuccess($tasks['data']);
    }

    public function store(TasksStoreRequest $request)
    {
        $data['task'] = $request['task'];
        $data['subject_id'] = $request['subject_id'];
        $data['user_id'] = $request['user_id'];
        $task = $this->taskRepository->createTask($data);

        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess(null, 'Thêm mới thành công');
    }

    public function show($id)
    {
        $task = $this->taskRepository->showTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    public function update(TasksUpdateRequest $request, $id)
    {
        $data['task'] = $request['task'];
        $data['subject_id'] = $request['subject_id'];
        $data['user_id'] = $request['user_id'];
        $task = $this->taskRepository->updateTask($data, $id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess(null, "Cập nhật thành công");
    }

    public function destroy($id)
    {
        $task = $this->taskRepository->deleteTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess(null, 'Xóa thành công');
    }

    public function getSubjectsByTaskId($id)
    {
        $task = $this->taskRepository->getSubjectOfTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    public function getUserTask($id)
    {
        $task = $this->taskRepository->getUserTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    public function updateComment(Request $request, $id)
    {
        $data['comment'] = $request['comment'];
        $data['status'] = $request['status'];
        $task = $this->taskRepository->updateComment($data, $id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess(null, 'Thêm mới thành công');
    }

    public function getUsersByTaskId($id)
    {
        $task = $this->taskRepository->getUsersByTaskId($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    public function assignUserToTask(Request $request, $id)
    {
        $userId = $request->only('user_id');
        $task = $this->taskRepository->assignUserToTaskById($userId, $id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                $task['message'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($task['message']);
    }
}
