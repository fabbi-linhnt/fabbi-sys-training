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
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_LIST_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($tasks['data']);
    }

    public function store(TasksStoreRequest $request)
    {
        $data['task'] = $request['task'];
        $data['subject_id'] = $request['subject_id'];
        $task = $this->taskRepository->createTask($data);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['ADD_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::TASK['ADD_SUCCESS']);
    }

    public function show($id)
    {
        $task = $this->taskRepository->showTask($id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['SHOW_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($task['data']);
    }

    public function update(TasksUpdateRequest $request, $id)
    {
        $data['task'] = $request['task'];
        $data['subject_id'] = $request['subject_id'];
        $task = $this->taskRepository->updateTask($data, $id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['UPDATE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::TASK['UPDATE_SUCCESS']);
    }

    public function destroy($id)
    {
        $task = $this->taskRepository->deleteTask($id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['DELETE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::TASK['DELETE_SUCCESS']);
    }

    public function getSubjectsByTaskId($id)
    {
        $task = $this->taskRepository->getSubjectsByTaskId($id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_SUBJECT_BY_TASK_ID_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($task['data']);
    }

    public function getUserTask($id)
    {
        $task = $this->taskRepository->getUserTask($id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_USER_TASK_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($task['data']);
    }

    public function updateComment(Request $request, $id)
    {
        $data['comment'] = $request['comment'];
        $data['status'] = $request['status'];
        $task = $this->taskRepository->updateComment($data, $id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['UPDATE_COMMENT_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::TASK['UPDATE_COMMENT_SUCCESS']);
    }

    public function getUsersByTaskId($id)
    {
        $task = $this->taskRepository->getUsersByTaskId($id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_USER_BY_TASK_ID_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
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

    public function getListUserByTaskId($id)
    {
        $data = $this->taskRepository->getListUserByTaskId($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_LIST_USER_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function getListSubjectByTaskId($id)
    {
        $data = $this->taskRepository->getListSubjectByTaskId($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['GET_LIST_USER_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function updateTimeTaskEnd(Request $request, $id)
    {
        $time = $request->only('start', 'end');
        $task = $this->taskRepository->updateTimeTaskEnd($time, $id);
        if (!$task['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::TASK['UPDATE_TIME_TASK_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess(ResponseMessage::TASK['UPDATE_TIME_TASK_SUCCESS']);
    }
}
