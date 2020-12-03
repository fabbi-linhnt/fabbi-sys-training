<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseStatusCode;
use App\Enums\ResponseStatus;
use App\Enums\ResponseMessage;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Controllers\Api\ApiBaseController;
use Facade\FlareClient\Http\Response;

class UserController extends ApiBaseController
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $inputData = $request->only('search', 'perPage');
        $data = $this->userRepository->getListUser($inputData);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['GET_LIST_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }
        return $this->sendSuccess($data['listUser']);
    }

    public function store(UserStoreRequest $request)
    {
        $inputData = $request->only(
            'name',
            'birthday',
            'phone',
            'address',
            'email',
            'password',
            'course_id',
            'img_path'
        );

        $inputData['password'] = bcrypt($inputData['password']);
        $data = $this->userRepository->addUser($inputData);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['ADD_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess('Add User Success');
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $inputData = $request->only(
            'name',
            'birthday',
            'phone',
            'address',
            'email',
            'password',
            'course_id',
            'img_path'
        );

        $inputData['password'] = bcrypt($inputData['password']);
        $user = $this->userRepository->updateUserById($inputData, $id);
        if (!$user['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['UPDATE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($user['message']);
    }

    public function destroy($id)
    {
        $data = $this->userRepository->deleteUserById($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['DELETE_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess("DELETE USER SUCCESS");
    }

    public function countSubject($id)
    {
        $data = $this->userRepository->countSubjectById($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['COUNT_SUBJECT_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function countTask($id)
    {
        $data = $this->userRepository->countTaskById($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['COUNT_TASK_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function userName($id)
    {
        $data = $this->userRepository->getUserNameById($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['GET_USER_NAME_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }

    public function getUserInfo($id)
    {
        $data = $this->userRepository->getUserInfoById($id);
        if (!$data['success']) {
            return $this->sendError(
                ResponseStatusCode::INTERNAL_SERVER_ERROR,
                ResponseMessage::USER['GET_USER_INFO_ERROR'],
                ResponseStatus::STATUS_ERROR
            );
        }

        return $this->sendSuccess($data['result']);
    }
}
