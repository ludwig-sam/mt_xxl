<?php namespace App\Repositorys\Admin;

use App\Models\ExeOpratorModel;
use App\Service\Users\AdminUser;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class ExeOpratorRepository extends Repository{

	public function model(){
		return ExeOpratorModel::class;
	}

	public function save($data){
		$this->model->fill($data);
		$this->model->save();
		return $this->model->id;
	}

	public function limit($limit, $request){
		$exeOprator = $this->model->getTable();
		$store = 'store';
		$where = [
			[$exeOprator.'.mch_id', AdminUser::getInstance()->getMchId()],
			[$exeOprator.'.username','like','%'. $request['username'].'%'],
			[$exeOprator.'.deleted_at','=',null]
		];
		return DB::table($exeOprator)
			->leftJoin($store,$exeOprator.".store_id","=",$store.".id")
			->select($exeOprator.'.*',$store.'.name as store_name')
			->when($request['store_id'], function($query) use ($request){
				return $query->where('store_id', $request['store_id']);
			})
			->when($request['status'], function($query) use ($request){
				return $query->where('status', $request['status']);
			})
			->where($where)
			->orderBy('id','desc')
			->paginate($limit);
	}

	public function opratorLists($limit,$request)
    {
        return DB::table('exe_oprator')
            //TODO store_id will be change
            ->where('mch_id', $request['mch_id'])
            ->where('store_id', $request['store_id'])
            ->paginate($limit);
    }

	public function updateOne(array $data,$where){
		$exe_oprator = $this->model->where($where)->first();
		$exe_oprator->fill($data);
		return $exe_oprator->save();
	}

	public function remove($where){
		return $this->model->where($where)->delete();
	}

	public function findOne($id,$where,$columns = array('*')){
		return $this->model->where($where)->find($id, $columns);
	}

	public function check($username){
		return $this->model->withTrashed()->where('username',$username)->first();
	}
}