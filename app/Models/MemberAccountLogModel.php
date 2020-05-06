<?php namespace App\Models;

use Abstracts\Offsetable;
use Libs\Filter;
use App\Models\Traits\DynamicWhereTrait;
use App\Service\Account\Account;
use App\Service\Export\Contracts\ExportSupportInterface;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MemberAccountLogModel extends Model implements ExportSupportInterface{

    use DynamicWhereTrait;

    protected $table = 'member_account_log';


    protected $fillable = [
        'name', 'value','comment','member_id','mch_id','mch_name','event_name','scene_name','order_id','created_at',
        'total'
    ];

    protected $dates = [
        'created_at'
    ];

    public function setUpdatedAt($value)
    {
    }

    public function getPointLimitByMember($member_id, $limit){
        return $this->getLimit($limit, [
            'name'      => Account::name_point,
            'member_id' => $member_id
        ]);
    }

    private function getLimit($limit, $where){
        return $this->where($where)
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

	public function getPointLimit($request,$limit){
		$request = new Collection($request);
		return  $this->filterQuery($request)->paginate($limit);;
	}

	function exportByIds($ids, $request){
		$fields = ['a.*', 'm.nickname', 'm.person_name', 'mch.name as mch_name'];

		return $this->exportFromLimit($ids, $fields);
	}

	function filterNoLimit($request){
		return  $this->filterQuery(new Collection($request))->get();
	}

	public function exportFromLimit($ids, $fields){

		return $this->from($this->table . ' as a')
			->select($fields)
			->join((new MemberModel())->getTable() . ' as m', 'm.id', '=', 'a.member_id')
			->leftJoin((new MchModel())->getTable() . ' as mch', 'mch.id', 'a.mch_id')
			->whereIn('a.id', $ids)
			->orderBy('id','desc')
			->get();
	}

	public function filterQuery(Collection $collection)
	{

		$likeName = $collection->get('search_by');

		$keywords = [
			'person_name',
			'nickname'
		];

		$model = $this->from($this->table . ' as a')
			->join((new MemberModel())->getTable() . ' as m', 'm.id', '=', 'a.member_id')
			->leftJoin((new MchModel())->getTable() . ' as mch', 'mch.id', 'a.mch_id')
			->when(in_array($likeName, $keywords) && $collection->get('keyword'), function ($query) use($collection, $likeName){
				return $query->where('m.' . $likeName, 'like', '%' . $collection->get('keyword') . '%');
			});

		$where = $this->dynamicEqWhere([
			'mch_id'
		], $collection);

		$model->where($where)->where('a.name', Account::name_point);

		$this->dateRange($model, $collection, 'begin_at', 'end_at', 'a.created_at');

		return $model
			->select('a.value', 'a.name', 'a.comment', 'a.total', 'a.created_at', 'm.headurl', 'm.nickname', 'm.person_name', 'mch.name as mch_name')
			->orderBy('a.id', 'desc');
	}

	public function cells($list){
		$result      = [];
		$header       = [
			"微信昵称","真实姓名","商户名称","积分变化","积分余额","备注","时间"
		];

		foreach ($list as $row){
			$result[] = [
				$row['nickname'],
				$row['person_name'],
				$row['mch_name'],
				$row['value'],
				$row['total'],
				$row['comment'],
				$row['created_at']
			];
		}

		return [$header, $result];
	}
}

