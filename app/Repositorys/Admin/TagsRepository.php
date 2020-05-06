<?php namespace App\Repositorys\Admin;


use App\Models\TagsModel;

class TagsRepository {

    private $model;

    public function __construct(TagsModel $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($data)
    {
        return $this->model->find($data['id'])->fill($data)->save();

    }

    public function list()
    {
        return $this->model->orderBy('id','desc')->get();
    }

    public function save($data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model->id;
    }


}