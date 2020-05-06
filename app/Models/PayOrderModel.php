<?php namespace App\Models;


use App\DataTypes\PayOrderStatus;
use App\Models\Traits\DynamicWhereTrait;

use App\PayConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Libs\Time;

class PayOrderModel extends Model
{
    use DynamicWhereTrait;


    public $table = 'pay_order';


    protected $primaryKey = "id";

    protected $fillable = [
        "amount", "attach", "auth_code", "cashier_id", "mch_id", "order_no", "store_id", "subject", "total_amount", "channel", "updated_at", "created_at", "status",
        "transaction_id", "payment_id", "payment_name", "refund_time", "member_id", "payment_at",
        "exe_id", "refund_amount"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function hasOneDetail()
    {
        return $this->hasOne(PayOrderDetailModel::class, 'order_id', 'id');
    }

    public function find($id)
    {
        return parent::with('hasOneDetail')->find($id);
    }

    public function findByOrderNo($orderNo)
    {
        return parent::with('hasOneDetail')->where("order_no", $orderNo)->first();
    }

    public function insert($data)
    {
        $model = $this->create($data);
        $model->hasOneDetail()->save(new PayOrderDetailModel($data));
        return $model;
    }

    private function getFill($data)
    {
        $result = [];

        $fillable = $this->fillable ?: $this->getAttributes();

        foreach ($fillable as $name) {
            if (isset($data[$name])) $result[$name] = $data[$name];
        }

        return $result;
    }

    public function edit($data)
    {

        if ($save_data = $this->getFill($data)) {
            $this->where("id", $this->id)->update($save_data);
        }

        $detailModel = new PayOrderDetailModel();

        if ($detail_data = $detailModel->getFill($data)) {
            $this->hasOneDetail->where('order_id', $this->id)->update($detail_data);
        }

        return true;
    }

    public function payList(Collection $offsetable, $limit)
    {

        $defineds = [
            "o.order_no",
            "o.payment_id",
            "o.status",
            "o.store_id"
        ];

        $model = $this->from($this->table . ' as o')
            ->leftJoin((new ExeOpratorModel())->getTable() . ' as op', 'op.id', '=', 'o.cashier_id')
            ->where('o.mch_id', $offsetable->get("mch_id"))
            ->where($this->dynamicEqWhere($defineds, $offsetable))
            ->select('o.*', 'op.username as cashier_username')
            ->orderBy('o.id', 'desc');

        $this->dateRange($model, $offsetable, 'sdate', 'edate', 'o.created_at');

        return $model->paginate($limit);
    }

    public function orderQuery($request)
    {
        $where = [
            ['order_no', '=', $request['order_no']],
        ];
        return $this->where($where)->first();
    }

    public function myAccountList($memberId, $limit, Collection $offsetable)
    {
        $model = $this->from($this->table . ' as o')
            ->leftJoin((new MchModel())->getTable() . ' as mch', 'o.mch_id', '=', 'mch.id')
            ->select('o.*', 'mch.name as mch_name');

        return $this->helperAccountWhere($model, $memberId, $offsetable)->orderBy('o.created_at', 'desc')->paginate($limit);
    }

    public function myAccountTotal($memberId, Collection $offsetable)
    {
        $model = $this->from($this->table . ' as o');
        return $this->helperAccountWhere($model, $memberId, $offsetable)->sum('amount');
    }

    public function myAccountCount($memberId, Collection $offsetable)
    {
        $model = $this->from($this->table . ' as o');

        return $this->helperAccountWhere($model, $memberId, $offsetable)->count();
    }

    private function helperAccountWhere($model, $memberId, Collection $offsetable)
    {
        $model = $model->where('member_id', $memberId)->whereIn('o.status', [PayOrderStatus::PAY_STATUS_SUCCES, PayOrderStatus::PAY_STATUS_REFUND]);
        $this->dateRange($model, $offsetable, 'begin_date', 'end_date', 'o.created_at');

        return $model;
    }

    public function getConsumeCardCount($sdate, $edate)
    {
        return $this->from($this->table . ' as o')
            ->join((new PayOrderDetailModel())->getTable() . ' as d', 'd.order_id', '=', 'o.id')
            ->whereIn('o.status', [PayOrderStatus::PAY_STATUS_SUCCES, PayOrderStatus::PAY_STATUS_REFUND])
            ->where('d.coupon_id', '!=', 0)
            ->when($sdate, function ($query) use ($sdate) {
                return $query->where('o.created_at', '>', $sdate);
            })
            ->when($edate, function ($query) use ($edate) {
                return $query->where('o.created_at', '<=', $edate);
            })
            ->count();
    }


    public function exportFromLimit($ids, $mch_id, $fields)
    {

        return $this->from($this->table . ' as pay_order')
            ->select($fields)
            ->leftJoin('exe_oprator', 'pay_order.cashier_id', '=', 'exe_oprator.id')
            ->leftJoin('store', 'pay_order.store_id', '=', 'store.id')
            ->leftJoin('pay_order_detail', 'pay_order.id', 'order_id')
            ->where('pay_order.mch_id', $mch_id)
            ->whereIn('pay_order.id', $ids)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function timeRangePayColelctionTotal($mch_id, $s_date, $e_date, $status)
    {
        $model = $this;

        $collection = new Collection([
            's_date' => $s_date,
            'e_date' => $e_date
        ]);

        $this->dateRange($model, $collection, 's_date', 'e_date', 'created_at');

        return $model
            ->where('mch_id', $mch_id)
            ->where('status', $status)
            ->sum('amount');
    }

    public function timeRangeCollectionTotalConsume($mch_id, $s_date, $e_date)
    {
        $model = $this->from($this->table . ' as o');

        $collection = new Collection([
            's_date' => $s_date,
            'e_date' => $e_date
        ]);

        $this->dateRange($model, $collection, 's_date', 'e_date', 'o.created_at');

        return $model
            ->join((new PayOrderDetailModel())->getTable() . ' as d', 'd.order_id', '=', 'o.id')
            ->whereIn('o.status', [PayOrderStatus::PAY_STATUS_SUCCES, PayOrderStatus::PAY_STATUS_REFUND])
            ->where('d.coupon_id', '!=', 0)
            ->where('mch_id', $mch_id)
            ->count();
    }

    public function total($status)
    {
        return $this->where('status', $status)->sum('amount');
    }


    public function totalBelongMch($status)
    {
        return $this->where('mch_id', '<>', 0)
            ->where('payment_id', PayConfig::PAYMENT_BALANCE)
            ->where('status', $status)->sum('amount');
    }

    public function totalBelongTheMch($status, $mch_id)
    {
        return $this->where('mch_id', $mch_id)
            ->where('payment_id', PayConfig::PAYMENT_BALANCE)
            ->where('status', $status)
            ->sum('amount');
    }

    public function totalWithPayment($payment_id, Collection $request)
    {
        $model = $this
            ->where('payment_id', $payment_id)
            ->where('status', PayOrderStatus::PAY_STATUS_SUCCES);

        $this->dateRange($model, $request, 'begin_at', 'end_at', 'payment_at');

        return $model
            ->sum('amount');
    }

    private function scopePayAtToday()
    {
        return $this->where('payment_at', '>=', Time::startToday())
            ->where('payment_at', '<=', Time::endToday());
    }

    public function totalToday($mch_id)
    {
        return $this->scopePayAtToday()
            ->where('mch_id', $mch_id)
            ->where('status', PayOrderStatus::PAY_STATUS_SUCCES)
            ->sum('amount');
    }

    public function countNumToday($mch_id)
    {
        return $this->scopePayAtToday()
            ->where('mch_id', $mch_id)
            ->whereIn('status', [PayOrderStatus::PAY_STATUS_SUCCES, PayOrderStatus::PAY_STATUS_REFUND])
            ->count();
    }
}

