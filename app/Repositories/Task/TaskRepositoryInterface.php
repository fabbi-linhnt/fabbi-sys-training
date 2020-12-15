<?php

namespace App\Repositories\Task;

interface TaskRepositoryInterface
{
    public function getTasks($request);
    public function createTask($request);
    public function showTask($id);
    public function deleteTask($id);
    public function updateTask($request, $id);
    public function getSubjectsByTaskId($id);
    public function getUserTask($id);
    public function updateComment($data, $id);
    public function getUsersByTaskId($id);
    public function assignUserToTaskById($userId, $id);
    public function getListUserByTaskId($id);
    public function getListSubjectByTaskId($id);
    public function updateTimeTaskEnd($time, $id);
}

