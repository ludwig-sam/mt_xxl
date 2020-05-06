<?php namespace App\Repositorys\Admin;

use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Libs\Arr;
use App\Models\ExeModel;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExeRepository extends Repository
{

    public function model()
    {
        return ExeModel::class;
    }

    public function createForMch($req, $mch_id)
    {
        $col = new Collection($req);
        $data = Arr::getIfExists($col->all(), [
            'comment',
            'status',
            'store_id',
            'card_id',
        ]);
        $data['dev_no'] = null;
        $data['mch_id'] = $mch_id;
        return $this->model->create($data);
    }

    public function limit($limit, $request, $mch_id)
    {
        $exe = $this->model->getTable();
        $where = [
            [$exe . '.mch_id', '=', $mch_id],
        ];
        return $this->model->leftJoin('store as s', $exe . ".store_id", "=", "s.id")->select($exe . '.*', 's.name as store_name')->when($request['store_id'], function ($query) use ($request) {
            return $query->where('store_id', $request['store_id']);
        })->where($where)->orderBy('id', 'desc')->paginate($limit);
    }

    public function get($id, $mch_id)
    {
        return $this->model->where('mch_id', $mch_id)->where('id', $id)->first();
    }

    public function check($row)
    {
        if (!$row) {
            throw  new ExceptionCustomCodeAble("此收银机不存在");
        }
    }

    public function getAndCheck($id, $mch_id)
    {
        $row = $this->get($id, $mch_id);
        $this->check($row);
        return $row;
    }

    public function updateForMch($req, $mch_id)
    {
        $col = new Collection($req);
        $row = $this->getAndCheck($col->get('id'), $mch_id);
        $data = Arr::getIfExists($col->all(), [
            'comment',
            'status',
            'store_id',
            'card_id',
        ]);
        return $row->update($data);
    }

    public function removeForMch($row)
    {
        return $row->delete();
    }

}