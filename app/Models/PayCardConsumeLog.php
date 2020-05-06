<?php namespace App\Models;

use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PayCardConsumeLog extends Model {

    use DynamicWhereTrait;


    protected $table = 'pay_card_consume_log';

    protected $fillable = [
        'card_id','card_title','code_id','code_no','order_no','exe_oprator_user_name','exe_dev_no','store_id','member_id','wx_card_id',
        'out_str'
    ];


    public $timestamps = false;


    public function limit($limit, $store_id, Collection $collection)
    {
        $model = $this->from($this->table . ' as log');

        $this->dynamicAnyWhere($model, [
            [
                'log.exe_dev_no',
                '=',
                'exe_dev_no'
            ]
        ], $collection);

        $this->dateRange($model, $collection, 'sdate', 'edate', 'log.created_at');

        return $model->where('store_id', $store_id)
            ->select('log.*', 'log.created_at as consume_at')
            ->orderBy('log.created_at', 'desc')
            ->paginate($limit);
    }


}

