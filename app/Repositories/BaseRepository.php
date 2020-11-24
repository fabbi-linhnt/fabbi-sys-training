<?php


namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $extend
     * @param int $paginate
     * @return array|false[]
     */
    public function getAll($extend = [], $paginate = 20)
    {
        $data = $this->model->with($extend)->paginate($paginate);
        if (count($data)) {
            return [
                'data' => $data,
                'success' => true,
            ];
        }

        return [
            'success' => false,
        ];
    }

    /**
     * @param $id
     * @param array $extend
     * @return array|false[]
     */
    public function getItemById($id, $extend = [])
    {
        try {
            $data = $this->model->with($extend)->findOrFail($id);

            return [
                'data' => $data,
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
            ];
        }
    }

    /**
     * @param $request
     * @return bool[]|false[]
     */
    public function createItem($request)
    {
        try {
            $this->model->create($request);

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
            ];
        }
    }

    /**
     * @param $id
     * @param $request
     * @return bool[]|false[]
     */
    public function editItem($id, $request)
    {
        try {
            $this->model->findOrFail($id)->update($request);
            
            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
            ];
        }
    }

    /**
     * @param $id
     * @return bool[]|false[]
     */
    public function destroyItem($id)
    {
        try {
            $this->model->destroy($id);

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
            ];
        }
    }
}

