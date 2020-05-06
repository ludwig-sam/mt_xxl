<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\DataTypes\PayOrderStatus;
use App\Models\PayOrderModel;
use Illuminate\Database\Eloquent\Model;

class  OrderRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->model()->find($id);

        $this->check($this->row, '支付订单');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():PayOrderModel
    {
        return $this->newSingle(PayOrderModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function amount()
    {
        return $this->row->amount;
    }

    public function paymentId()
    {
        return $this->row->payment_id;
    }

    public function orderNo()
    {
        return $this->row->order_no;
    }

    public function mchId()
    {
        return $this->row->mch_id;
    }

    public function status()
    {
        return $this->row->status;
    }

    public function isSuccess()
    {
        return $this->status() == PayOrderStatus::PAY_STATUS_SUCCES;
    }

    public function isPeding()
    {
        return $this->status() == PayOrderStatus::PAY_STATUS_PENDING;
    }

    public function memberId()
    {
        return $this->row->member_id;
    }

    public function refundAmount()
    {
        return $this->row->refund_amount;
    }

    public function hasDetail()
    {
        return $this->row->hasOneDetail;
    }

    public function point()
    {
        return $this->hasDetail()->point;
    }

    public function exp()
    {
        return $this->hasDetail()->exp;
    }

}