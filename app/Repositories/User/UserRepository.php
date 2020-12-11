<?php

namespace App\Repositories\User;

use App\Enums\ResponseMessage;
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
        DB::beginTransaction();

        try {
            if ($data['img_path'] == '') {
                $data['img_path'] = config('configuser.images_default');
            }
            $user = $this->user->create($data);
            $user->image()->create(['path' => $data['img_path']]);
            $user->courses()->attach($data['course_id'], ['status' => config('configcourse.status_user_inactive')]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'success' => true
        ];
    }

    public function getListUser(array $data)
    {
        try {
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
        catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
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
           $courseParticipatedName = array();
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
                'success' => false,
                'message' => $e->getMessage()
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
                'success' => true,
                'message' => ResponseMessage::USER['DELETE_SUCCESS']
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

    public function updateUserById($data, $id)
    {
        DB::beginTransaction();
        try {
            if ($data['img_path'] == '') {
                $data['img_path'] = config('configuser.images_default');
            }
            $userData = $this->user->findOrFail($id);
            $userData->update($data);
            $course_id = $data['course_id'];
            $userData->courses()->detach();
            $userData->courses()->attach($course_id, ['status' => 'Update']);
            $userData->image()->create(['path' => $data['img_path']]);

            DB::commit();
            return [
                'success' => true,
                'message' => ResponseMessage::USER['UPDATE_SUCCESS']
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
