<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/7
 * Time: 下午5:27
 */

namespace Tests\Notify;


use App\Jobs\ProcessPaySuccessOrder;
use App\Jobs\ProcessRefundOrder;
use App\Models\PayOrderModel;
use Tests\TestCase;

class PaySuccessNotify extends TestCase
{

    private function getOrder()
    {
        $order_id    = 571;

        $order_model = new PayOrderModel();

        return $order_model->find($order_id);
    }

    public function test_success()
    {
        dispatch(new ProcessPaySuccessOrder($this->getOrder()));

        $this->assertEquals(true, true);
    }

    public function test_refund()
    {
        dispatch(new ProcessRefundOrder($this->getOrder()));

        $this->assertEquals(true, true);
    }

}