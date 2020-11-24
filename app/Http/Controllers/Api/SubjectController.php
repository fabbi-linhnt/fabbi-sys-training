<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Subjects\SubjectInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(SubjectInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/subjects?name={name}&perPage={perPage}&page={page}",
     *      description="Get subjects",
     *      tags={"Subject"},
     *      @OA\Parameter(in="query",name="name",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="perPage",@OA\Schema(type="string")),
     *      @OA\Parameter(in="query",name="page",@OA\Schema(type="string")),
     *      @OA\Response(
     *          response="200",
     *          description="response subjects success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "data": {
     *                          {
     *                           "id": 1,
     *                           "name": "vstacqyjU9",
     *                           "description": "pFwFsBuiRc",
     *                           "is_active": 1,
     *                           "created_at": null,
     *                           "updated_at": null
     *                           },
     *                       },
     *                       "current_page":1,
     *                       "first_page_url": "http://localhost:2222/api/subjects?page=1",
     *                       "from": 1,
     *                       "last_page": 1,
     *                       "last_page_url": "http://localhost:2222/api/subjects?page=1",
     *                       "next_page_url": null,
     *                       "path": "http://localhost:2222/api/subjects",
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
        $subjects = $this->repository->getListSubject($request);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * @OA\Post(
     *      path="/api/subjects",
     *      description="Store subject",
     *      tags={"Subject"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="subject",
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="is_active", type="boolean"),
     *              ),
     *              @OA\Property(
     *                  property="course_id",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Store subject success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->repository->createSubject($data);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("ADD SUBJECT SUCCESS");
    }

    /**
     * @OA\get(
     *     path="/api/subjects/{id}",
     *     description="show subject detail",
     *     tags={"Subject"},
     *     @OA\Parameter(
     *         name="id",
     *         description="subject id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="response subject success",
     *         @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      "id": 10,
     *                      "name": "rxe8KR5xqc",
     *                      "description": "RzmagvNtXV",
     *                      "is_active": 1,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  }
     *              }
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function show($id)
    {
        $subjects = $this->repository->getSubjectbyId($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * @OA\Put(
     *      path="/api/subjects",
     *      description="Update subject",
     *      tags={"Subject"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="subject",
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="is_active", type="boolean"),
     *              ),
     *              @OA\Property(
     *                  property="course_id",
     *                  type="array",
     *                  @OA\Items(type="integer")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Update subject success"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Missing or invalid data"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('name', 'description', 'is_active', 'course_id');
        $subjects = $this->repository->updateSubject($data, $id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
    }

    /**
     * @OA\Delete(
     *     path="/api/subjects/{id}",
     *     description="delete subject",
     *     tags={"Subject"},
     *     @OA\Parameter(
     *         name="id",
     *         description="subject id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="delete subject success"
     *     ),
     * )
     */
    public function destroy($id)
    {
        //
        $subjects = $this->repository->deleteSubject($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }

    /**
     * @OA\get(
     *     path="/api/subjects/count/{id}",
     *     description="show amount users, courses, tasks of subject",
     *     tags={"Subject"},
     *     @OA\Parameter(
     *         name="id",
     *         description="subject id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="response subject success",
     *         @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      4, 6, 5
     *                  }
     *              }
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid id"
     *     ),
     * )
     */
    public function countTaskCourseSubjectById($id)
    {
        $subjects = $this->repository->countTaskCourseSubjectById($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * @OA\Get(
     *      path="/api/subjects/courses/{id}",
     *      description="get course in subject",
     *      tags={"Subject"},
     *      @OA\Parameter(
     *         name="id",
     *         description="subject id",
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
     *                       {
     *                          "id": 3,
     *                          "name": "fPhXEcJx1v",
     *                          "description": "Oqwsjx927x",
     *                          "is_active": 0,
     *                          "category_id": 9,
     *                          "created_at": null,
     *                          "updated_at": null,
     *                          "pivot": {
     *                              "subject_id": 1,
     *                              "course_id": 3
     *                          }
     *                       },
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function ListCourseBySubjetID($id)
    {
        $subjects = $this->repository->ListCourseBySubjetID($id);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * @OA\Get(
     *      path="/api/courses/list",
     *      description="get all course",
     *      tags={"Subject"},
     *      @OA\Parameter(
     *         name="id",
     *         description="subject id",
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
     *                       {
     *                           "id": 1,
     *                           "name": "sr7Mlx8itY",
     *                           "description": "Wd00fJflFQ",
     *                           "is_active": 0,
     *                           "category_id": 4,
     *                           "created_at": "2020-12-12",
     *                           "updated_at": "2020-12-12"
     *                       },
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function listCourses()
    {
        $courses = $this->repository->listCourses();
        if (!$courses['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($courses['data']);
    }

    /**
     * @OA\Put(
     *      path="/api/subjects/is-active/{id}",
     *      description="update is_active subject",
     *      tags={"Subject"},
     *      @OA\Parameter(
     *         name="id",
     *         description="subject id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="update is_acive success",
     *      ),
     *     @OA\Response(
     *          response=422
     *     )
     * )
     */
    public function updateActive($id)
    {
        $subject = $this->repository->updateActive($id);
        if (!$subject['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("UPDATE SUBJECT SUCCESS");
    }

    /**
     * @OA\Get(
     *      path="/api/subjects/all",
     *      description="Get list subject",
     *      tags={"Subject"},
     *      @OA\Response(
     *          response="200",
     *          description="Response success",
     *          @OA\JsonContent(
     *              type="object",
     *              examples={
     *                  "data": {
     *                      {
     *                          "id": 1,
     *                          "name": "vstacqyjU9",
     *                          "description": "pFwFsBuiRc",
     *                          "is_active": 1,
     *                          "created_at": "2020-12-12",
     *                          "updated_at": "2020-12-12"
     *                       },
     *                  },
     *             },
     *          ),
     *      ),
     * )
     */
    public function getAllSubject()
    {
        $subjects = $this->repository->getAllSubject();
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }
}
