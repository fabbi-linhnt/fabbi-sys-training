<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends ApiBaseController
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/tasks?name={name}&perPage={perPage}&page={page}",
     *      description="Get task",
     *      tags={"Task"},
     *      @OA\Parameter(name="name",@OA\Schema(type="string")),
     *      @OA\Parameter(name="perPage",@OA\Schema(type="string")),
     *      @OA\Parameter(name="page",@OA\Schema(type="string")),
     *      @OA\Response(
     *          response="200",
     *          description="response tasks success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": {
     *                          "id":"1",
     *                          "name":"task1",
     *                          "description": "dCXatgvWk2",
     *                          "content": "rPeYC62M5k",
     *                           "deadline": "2020-10-10",
     *                           "is_active": 1,
     *                           "created_at": "2020-10-10",
     *                           "updated_at": "2020-10-10",
     *                       },
     *                       "current_page":1,
     *                       "first_page_url": "http://localhost:2222/api/tasks?page=1",
     *                       "from": 1,
     *                       "last_page": 1,
     *                       "last_page_url": "http://localhost:2222/api/tasks?page=1",
     *                       "next_page_url": null,
     *                       "path": "http://localhost:2222/api/tasks",
     *                       "per_page": 15,
     *                       "prev_page_url": null,
     *                       "to": 10,
     *                       "total": 10
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $tasks = $this->taskRepository->getTasks($request);
        if (!$tasks['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($tasks['data']);
    }

    /**
     * @OA\Post(
     *      path="/api/tasks",
     *      description="Store task",
     *      tags={"Task"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="task",
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="content", type="string"),
     *                  @OA\Property(property="deadline", type="string", format="date"),
     *                  @OA\Property(property="is_active", type="boolean"),
     *              ),
     *              @OA\Property(
     *                  property="subject_id",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Store task success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function store(Request $request)
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

    /**
     * @OA\get(
     *     path="/api/tasks/{id}",
     *     description="show task detail",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         name="id",
     *         description="task id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="response task success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function show($id)
    {
        $task = $this->taskRepository->showTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    /**
     * @OA\Put(
     *      path="/api/tasks/{id}",
     *      description="Update task",
     *      tags={"Task"},
     *      @OA\Parameter(
     *          name="id",
     *          description="task id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="task",
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="content", type="string"),
     *                  @OA\Property(property="deadline", type="string", format="date"),
     *                  @OA\Property(property="is_active", type="boolean"),
     *              ),
     *              @OA\Property(
     *                  property="subject_id",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Update task success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function update(Request $request, $id)
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

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     description="delete task",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         name="id",
     *         description="task id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="delete task success"
     *     ),
     * )
     */
    public function destroy($id)
    {
        $task = $this->taskRepository->deleteTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess(null, 'Xóa thành công');
    }

    /**
     * @OA\Get(
     *      path="/api/tasks/subjects/{id}",
     *      description="Get subjects in task",
     *      tags={"Task"},
     *      @OA\Parameter(
     *         name="id",
     *         description="task id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="response success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                       "id": 3,
     *                       "name": "Yytfgr9MMY",
     *                       "description": "rfzJwaZg77",
     *                       "is_active": 0,
     *                       "created_at": null,
     *                       "updated_at": null,
     *                       "pivot": {
     *                          "task_id": 1,
     *                          "subject_id": 3
     *                       }
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function getSubjectOfTask($id)
    {
        $task = $this->taskRepository->getSubjectOfTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    /**
     * @OA\Get(
     *      path="/api/tasks/users/{id}",
     *      description="get users in task",
     *      tags={"Task"},
     *      @OA\Parameter(
     *         name="id",
     *         description="task id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="response success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      {
     *                       "id": 2,
     *                       "user_id": 3,
     *                       "task_id": 1,
     *                       "status": 0,
     *                       "report": "w",
     *                       "date_submit": "2020-10-11",
     *                       "comment": "g",
     *                       "created_at": null,
     *                       "updated_at": null,
     *                       "username": "xVFSxy1JsA",
     *                       "deadline": "2020-10-10",
     *                       "name": "QW0QFHxZD4"
     *                      }
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function getUserTask($id)
    {
        $task = $this->taskRepository->getUserTask($id);
        if (!$task['success']) {
            return $this->sendError(500, $task['message'], 'failed');
        }

        return $this->sendSuccess($task['data']);
    }

    /**
     * @OA\Put(
     *      path="/api/tasks/users/{id}",
     *      description="update comment user_task",
     *      tags={"Task"},
     *      @OA\Parameter(
     *         name="id",
     *         description="user_task id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="comment", type="string"),
     *              @OA\Property(property="status", type="boolean")
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Update comment success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
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
}

