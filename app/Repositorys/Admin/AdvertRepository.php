<?php namespace App\Repositorys\Admin;


use App\Models\AdvertModel;
use Bosnadev\Repositories\Eloquent\Repository;


class AdvertRepository extends Repository {

    public function model()
    {
        return AdvertModel::class;
    }

    public function save($data)
    {
        $this->model->fill($data);
        return $this->model->save();
    }

    public function advertList($advert_position_id,$limit){
    	return $this->model->where('advert_position_id',$advert_position_id)->orderBy('sort','desc')->paginate($limit);
    }

    public function getAdvert($id){
		return $this->model->find($id);
    }

    public function update(array $data, $id){
	    return $this->model->find($id)->fill($data)->save();
    }
}