<?php namespace App\Repositorys\Admin;


use App\Models\MchCategoryModel;

class MchCategoryRepository {

    private $model;

    public function __construct(MchCategoryModel $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($data)
    {
        $MC =  $this->model->find($data['id'])
            ->fill($data)
            ->save();
        return $MC;

     }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function save($data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model->id;
    }

    public function page($limit, $where = []){
        if($where){
            return $this->model->where($where)->paginate($limit);
        }
        return $this->model->paginate($limit);
    }

    public function validList(){
        return $this->model->where('is_use', 1)->get();
    }

    public function all(){
        return $this->model->orderBy('id','desc')->get();
    }

}