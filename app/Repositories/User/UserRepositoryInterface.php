<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function addUser(array $data);
    public function getListUser(array $data);
    public function countSubjectById($id);
    public function countTaskById($id);
    public function getUserNameById($id);
    public function getUserInfoById($id);
    public function deleteUserById($id);
    public function updateUserById($data,$id);
}

