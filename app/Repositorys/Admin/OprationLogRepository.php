<?php namespace App\Repositorys\Admin;

use App\Models\OprationLogModel;
use App\Service\Export\Contracts\ExportSupportInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Libs\Time;

class OprationLogRepository extends Repository implements ExportSupportInterface{

	public function model(){
		return OprationLogModel::class;
	}

	public function limit($limit,$request){

		return $this->filterQuery(new Collection($request),$limit)->paginate($limit);;
	}

	public function filterQuery(Collection $request){
		$request['begin_date'] = $request->get('begin_date') !== null ? $request->get('begin_date') : Time::date(0);
		$request['end_date'] = $request->get('end_date') !== null ? $request->get('end_date') : Time::date();

		return $this->model->from($this->model->getTable() . ' as o')
			->leftJoin('admin as a',"o.user_id","=","a.id")
			->select('o.*','a.user_name as operator_name')
			->whereBetween('o.created_at',[$request->get("begin_date"),$request->get('end_date')])
			->orderBy('id','desc');
	}

	function exportByIds($ids, $request){

		$fields = ['o.*','a.user_name as operator_name'];

		return $this->model->exportFromLimit($ids, $fields);
	}

	function filterNoLimit($request){
		return $this->filterQuery(new Collection($request))->get();
	}

	public function cells($list){
		$result      = [];
		$header       = [
			"ID","操作员","操作","操作详情","操作时间"
		];

		foreach ($list as $row){
			$result[] = [
				$row['id'],
				$row['operator_name'],
				$row['title'],
				$row['detial'],
				$row['created_at']
			];
		}

		return [$header, $result];
	}
}