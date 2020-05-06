<?php namespace App\Repositorys\Mchs;


use App\Models\PayMethodModel;
use Bosnadev\Repositories\Eloquent\Repository;


class PayMethodRepository extends Repository {

    public function model()
    {
        return PayMethodModel::class;
    }

    public function limit(){
		return $this->model->select('id as payment_id','name as payment_name')->orderBy('id','desc')->get();
    }
}