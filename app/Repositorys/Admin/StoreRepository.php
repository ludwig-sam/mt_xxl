<?php namespace App\Repositorys\Admin;


use App\Models\StoreModel;
use Bosnadev\Repositories\Eloquent\Repository;


class StoreRepository extends Repository
{

    public function model()
    {
        return StoreModel::class;
    }

    public function save($data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model->id;
    }

    public function limit($limit, $request, $mch_id)
    {
        return $this->model->where('mch_id', $mch_id)
            ->when($request['name'], function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request['name'] . '%');
            })
            ->when($request['status'], function ($query) use ($request) {
                return $query->where('status', $request['status']);
            })
            ->orderBy('id', 'desc')->paginate($limit);
    }

    public function updateOne(array $data, $where)
    {
        return $this->model->where($where)->first()->update($data);
    }

    public function remove($where)
    {
        return $this->model->where($where)->delete();
    }

    public function findOne($id, $where, $columns = array('*'))
    {
        return $this->model->where($where)->find($id, $columns);
    }
}