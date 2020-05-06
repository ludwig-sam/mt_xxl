<?php namespace App\Models;


use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PayRefundModel extends Model {
    use DynamicWhereTrait;

    protected $table = 'pay_refund_order';

    protected $primaryKey = "id";

    protected $fillable = [
        "order_id", "refund_no", "channel_refund_id", "order_no",
        "transaction_id", "refund_amount","created_at"
    ];

    protected $dates = [
        'created_at'
    ];

    public function setUpdatedAt($value)
    {
    }

    public function refundList(Collection $offsetable, $limit)
    {

        $defineds = [
            "or.order_no",
            "payment_id",
            "o.store_id"
        ];

        $require = [
            "o.mch_id" => $offsetable->get("mch_id"),
        ];

        $model = $this->from($this->table . ' as or')
            ->join((new PayOrderModel)->getTable() . ' as o', 'or.order_id', '=', 'o.id')
            ->leftJoin((new ExeOpratorModel())->getTable() . ' as op' , 'op.id', '=', 'o.cashier_id')
            ->select('or.*', 'o.payment_id', 'o.payment_name', 'o.cashier_id', 'o.store_id', 'o.mch_id', 'op.username as cashier_username')
            ->where($require)
            ->where($this->dynamicEqWhere($defineds, $offsetable))
            ->orderBy('or.id', 'desc');

        $this->dateRange($model, $offsetable, 'sdate', 'edate', 'or.created_at');

        return $model->paginate($limit);
    }

    public function refundQuery($request){
	    $where=[
		    ['order_no','=',$request['order_no']],
	    ];
	    return $this->where($where)->first();
    }
}

