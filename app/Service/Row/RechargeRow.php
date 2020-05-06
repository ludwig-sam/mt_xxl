<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\DataTypes\RecharegeStatus;
use App\Models\RechargeModel;
use Illuminate\Database\Eloquent\Model;

class  RechargeRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '充值订单');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():RechargeModel
    {
        return $this->newSingle(RechargeModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function orderNo()
    {
        return $this->row->order_no;
    }

    public function paymentId()
    {
        return $this->row->payment_id;
    }

    public function memberId()
    {
        return $this->row->member_id;
    }

    public function status()
    {
        return $this->row->status;
    }

    public function amount()
    {
        return $this->row->amount;
    }

    public function isSuccess()
    {
        return $this->status() == RecharegeStatus::success;
    }
}