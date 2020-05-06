<?php namespace App\Repositorys\Admin;


use App\Models\FictitiousCardModel;
use Bosnadev\Repositories\Eloquent\Repository;


class FictitiousCardRepository extends Repository {

    public function model()
    {
        return FictitiousCardModel::class;
    }

    public function save($data)
    {
        $data['batch_oprration_number'] = null;
        $this->model->fill($data);
        $this->model->save();
        return $this->model->id;
    }

}