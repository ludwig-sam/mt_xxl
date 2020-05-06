<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/7
 * Time: 下午5:27
 */

namespace Tests\Notify;


use App\Jobs\ProcessPaySuccessOrder;
use App\Models\PayOrderExtendsFunLogModel;
use App\Models\PayOrderModel;
use Tests\Api\WebBase;

class PayAsynceTaskTest extends WebBase
{

    public function test_notify()
    {
        $orderId = 243;
        $model   = new PayOrderModel();
        $order   = $model->find($orderId);

        $sendLogModel  = new PayOrderExtendsFunLogModel();

        $sendLogModel->where('order_id', $orderId)->first()->delete();

        $sendLogModel->create(['order_id' => $orderId]);

        \dispatch(new ProcessPaySuccessOrder($order));

        $payExtendsModel = new PayOrderExtendsFunLogModel();

        $row = $payExtendsModel->where('order_id', $orderId)->first();

        $this->assertTrue( 'success' ==  $row->send_card_execute || 'pending' == $row->send_card_execute);
    }

}