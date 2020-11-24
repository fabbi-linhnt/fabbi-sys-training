<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Controllers\Api\ApiBaseController;

class UserController extends ApiBaseController
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/users?name={name}&perPage={perPage}&page={page}",
     *      description="Get users",
     *      tags={"User"},
     *      @OA\Parameter(in="query",name="name",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="perPage",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="page",@OA\Schema(type="string")),
     *      @OA\Response(
     *          response="200",
     *          description="response courses success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": {
     *                          "id": 1,
     *                          "name": "eVGn9hM53X",
     *                          "email": "QbxJyjMWYQ@gmail.com",
     *                          "phone": null,
     *                          "address": "pCbU4hqvvt",
     *                          "birthday": null,
     *                          "email_verified_at": null,
     *                          "created_at": null,
     *                          "updated_at": null
     *                       },
     *                       "current_page":1,
     *                       "first_page_url": "http://localhost:2222/api/courses?page=1",
     *                       "from": 1,
     *                       "last_page": 1,
     *                       "last_page_url": "http://localhost:2222/api/courses?page=1",
     *                       "next_page_url": null,
     *                       "path": "http://localhost:2222/api/courses",
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
        $inputData = $request->only('search', 'perPage');
        $data = $this->repository->getListUser($inputData);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess($data['listUser']);
    }

    /**
     * @OA\Post(
     *      path="/api/users",
     *      description="Store user",
     *      tags={"User"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="phone", type="string"),
     *              @OA\Property(property="address", type="string"),
     *              @OA\Property(property="birthday", type="string", format="date"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="course", type="integer"),
     *              @OA\Property(property="img_path", type="string"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Store user success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $inputData = $request->only(
            'name',
            'birthday',
            'phone',
            'address',
            'email',
            'password',
            'course',
            'img_path'
        );

        $inputData['password'] = bcrypt($inputData['password']);
        $data = $this->repository->addUser($inputData);
        if ($data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess('Add User Success');
    }

    /**
     * @OA\Put(
     *      path="/api/users/{id}",
     *      description="Update user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          required=true,
     *          description="id user",
     *          name="id",
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="phone", type="string"),
     *              @OA\Property(property="address", type="string"),
     *              @OA\Property(property="birthday", type="string", format="date"),
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="course", type="integer"),
     *              @OA\Property(property="img_path", type="string"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Update user success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->only(
            'name',
            'birthday',
            'phone',
            'address',
            'email',
            'password',
            'course',
            'img_path'
        );

        $inputData['password'] = bcrypt($inputData['password']);
        $user = $this->repository->updateUserById($inputData, $id);
        if (!$user['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess("UPDATE USER SUCCESS");
    }

    /**
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      description="Delete user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Delete user success"
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Invalid id user"
     *      ),
     * )
     */
    public function destroy($id)
    {
        $data = $this->repository->deleteUserById($id);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess("DELETE USER SUCCESS");
    }

    /**
     * @OA\Get(
     *      path="/api/users/subjects/{id}",
     *      description="count subjects of user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Return amount subjects success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": 99
     *                  }
     *              },
     *          )
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Invalid id"
     *      ),
     * )
     */
    public function countSubject($id)
    {
        $data = $this->repository->countSubjectById($id);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess($data['result']);
    }

    /**
     * @OA\Get(
     *      path="/api/users/tasks/{id}",
     *      description="count tasks of user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Return amount tasks success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": 99
     *                  }
     *              },
     *          )
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Invalid id"
     *      ),
     * )
     */
    public function countTask($id)
    {
        $data = $this->repository->countTaskById($id);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess($data['result']);
    }


    /**
     * @OA\Get(
     *      path="/api/users/name/{id}",
     *      description="Get name of user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Return username success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": "username"
     *                  }
     *              },
     *          )
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Invalid id"
     *      ),
     * )
     */
    public function userName($id)
    {
        $data = $this->repository->getUserNameById($id);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess($data['result']);
    }


    /**
     * @OA\Get(
     *      path="/api/userss/getInfo/{id}",
     *      description="Get info course of user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Return username success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "name": "username",
     *                      "numberSubject": 50,
     *                      "numberTask": 150,
     *                      "courseParticipatedName": {
     *                         "bY6WUEE67v",
     *                         "rQwlaEbWJN",
     *                         "CkDDEBWlr8"
     *                      }
     *                  }
     *              },
     *          )
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Invalid id"
     *      ),
     * )
     */
    public function getUserInfo($id)
    {
        $data = $this->repository->getUserInfoById($id);
        if (!$data['success']) {
            return $this->sendError(500, "Error", "Failed");
        }

        return $this->sendSuccess($data['result']);
    }
}
