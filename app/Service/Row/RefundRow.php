<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\Models\PayRefundModel;
use Illuminate\Database\Eloquent\Model;

class  RefundRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '退款订单');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():PayRefundModel
    {
        return $this->newSingle(PayRefundModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function refundAmount()
    {
        return $this->row->refund_amount;
    }

    public function orderId()
    {
        return $this->row->order_id;
    }
}