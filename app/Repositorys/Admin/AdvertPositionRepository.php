<?php namespace App\Repositorys\Admin;


use App\Models\AdvertPositionModel;
use Bosnadev\Repositories\Eloquent\Repository;


class AdvertPositionRepository extends Repository {

    public function model()
    {
        return AdvertPositionModel::class;
    }

    public function limit($position_key){
    	return $this->model->where('position_key','=',$position_key)->first()->adverts;
    }

}