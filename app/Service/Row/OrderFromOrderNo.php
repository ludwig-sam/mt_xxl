<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


class  OrderFromOrderNo extends OrderRow
{

    public function __construct($order_no)
    {
        $this->row = $this->model()->findByOrderNo($order_no);
        $this->check($this->row, '订单:' . $order_no);
    }
}