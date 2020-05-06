<?php namespace App\Models;

use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PayNotifyUserModel extends Model {

    use DynamicWhereTrait;

    protected $table = 'pay_notify_user';


    public $fillable = [
        'fans_id','openid','mch_id'
    ];

    public $timestamps = false;


    public function getLimitWithFans($limit, Collection $collection)
    {

        $search_bys = [
            "nickname"
        ];

        $definds = [
            [
                "f.nickname",
                "like",
                "nickname"
            ]
        ];

        $search_by = $collection->get('search_by');

        if(in_array($search_by, $search_bys)){
            $collection->offsetSet($search_by, $collection->get('keywords'));
        }

        $model = $this->from($this->table . ' as n');

        $this->dynamicAnyWhere($model, $definds, $collection);

        return $model
            ->leftJoin((new FansModel())->getTable() . ' as f', 'f.id', '=', 'n.fans_id')
            ->select('f.*', 'n.id as id')
            ->paginate($limit);
    }

    public function getPayNotifyOpenids($mch_id = 0)
    {
        $list = $this->whereIn('mch_id', [$mch_id, 0])->get()->toArray();

        return array_column($list, 'openid');
    }

    public function getByFans($fans_id)
    {
        return $this->where("fans_id", $fans_id)->first();
    }
}

