<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PayOrderExtendsFunLogModel extends Model
{
    protected $table = 'pay_order_extends_function_log';

    protected $fillable = [
        "order_id", "point_execute", "exp_execute", "card_consume_execute", "balance_execute", "send_card_execute"
    ];

    public function getByOrderId($order_id)
    {
        return $this->where('order_id', $order_id)->first();
    }

    public function createNew($order_id)
    {
        return $this->create([
            'order_id' => $order_id
        ]);
    }

}