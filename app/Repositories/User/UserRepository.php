<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class EquipmentRepository
 * @package App\Repositories\Equipment
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }

    public function addUser(array $data)
    {
        try {
           $user = $this->user->create($data);
           $userData = $this->user->find($user->id);
           $courseId = $data['course'];
           $userData->courses()->attach($courseId, ['status' => 'Create']);

           return [
               'success' => true
           ];
        }
        catch(\Exception $e) {
            return [
                'success' => false
            ];
        }
    }

    public function getListUser(array $data)
    {
       $list = $this->model;
       $perPage = $data['perPage'];
       if(array_key_exists('search', $data) && !empty($data['search']))
       {
          $input = $data['search'];
          $list = $list->where('name', 'LIKE', "%$input%");
       }

       return [
          'success' => true,
          'listUser' => $list->paginate($perPage)
       ];
    }

    public function countSubjectById($id)
    {
        try {
            $userData = $this->user->findOrFail($id);
            $result = $userData->subjects()->where('user_id', $id)->count();

            return [
                'success' => true,
                'result' => $result
            ];
        }
        catch(\Exception $e)
        {
           return [
               'success' => false
           ];
        }
    }

    public function countTaskById($id)
    {
        try {
            $userData = $this->user->findOrFail($id);
            $result = $userData->tasks()->where('user_id', $id)->count();

            return [
                'success' => true,
                'result' => $result
            ];
        }
        catch(\Exception $e)
        {
           return [
               'success' => false
           ];
        }
    }

    public function getUserNameById($id)
    {
        try {
            $userData = $this->user->findOrFail($id);
            $name = $userData->name;

            return [
                'success' => true,
                'result' => $name
            ];
        }
        catch(\Exception $e)
        {
           return [
               'success' => false
           ];
        }
    }

    public function getUserInfoById($id)
    {
       try {
           $userData = $this->user->findOrFail($id);
           $name = $userData->name;
           $numberTask = $userData->tasks()->where('user_id', $id)->count();
           $numberSubject = $userData->subjects()->where('user_id', $id)->count();
           $courseParticipatedInfo = $userData->courses()->where('user_id', $id)->select('course_id')->get()->toArray();
           foreach ($courseParticipatedInfo as $value)
           {
                $courseParticipatedName[] = DB::table('courses')->where('id', $value['course_id'])->value('name');
           }
           $result = array(
                "name" => $name,
                "numberSubject" => $numberSubject,
                "numberTask" => $numberTask,
                "courseParticipatedName" => $courseParticipatedName
            );

           return [
               'success' => true,
               'result'  => $result
           ];
       }
       catch(\Exception $e)
       {
           return [
               'success' => false
           ];
       }
    }

    public function deleteUserById($id)
    {
        try
        {
           $userData = $this->user->findOrFail($id);
           $userData->subjects()->detach();
           $userData->courses()->detach();
           $userData->tasks()->detach();
           $userData->delete();

           return [
               'success' => true
           ];
        }
        catch(\Exception $e)
        {
           return [
               'success' => false,
               'message' => $e->getMessage()
           ];
        }
    }

    public function updateUserById($data,$id)
    {
       try
       {
          $userData = $this->user->findOrFail($id);
          $userData->update($data);
          $course_id = $data['course'];
          $userData->courses()->detach();
          $userData->courses()->attach($course_id, ['status' => 'Update']);

          return [
              'success' => true
          ];
       }
       catch(\Exception $e)
       {
          return [
              'success' => false,
              'message' => $e->getMessage()
          ];
       }
    }
}
