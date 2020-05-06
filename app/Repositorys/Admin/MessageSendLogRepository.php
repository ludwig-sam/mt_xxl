<?php namespace App\Repositorys\Admin;


use App\Models\MessageSendLogModel;
use Bosnadev\Repositories\Eloquent\Repository;

class MessageSendLogRepository extends  Repository{

	public function model(){
		return MessageSendLogModel::class;
	}

	public function limit($limit,$where){
		return $this->model->select('id','type','touser','status','comment','operator','created_at')
			->where($where)
			->paginate($limit);
	}
}