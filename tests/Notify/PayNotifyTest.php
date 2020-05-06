<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/7
 * Time: 下午5:27
 */

namespace Tests\Notify;


use Libs\Route;
use App\Models\PayOrderExtendsFunLogModel;
use App\Models\PayOrderModel;
use Tests\Api\WebBase;

class PayNotifyTest extends WebBase
{

    public function test_notify()
    {
        $orderId = 567;

        $payOrderModel = new PayOrderModel();
        $sendLogModel  = new PayOrderExtendsFunLogModel();

        $sendLogModel->where('order_id', $orderId)->first()->delete();

        $sendLogModel->create(['order_id' => $orderId]);

        $payOrderModel->find($orderId)->update(['status' => 'PENDING']);


        $this->post(Route::named('pay_notify', [$orderId]), [
            'trade_status' => 'TRADE_SUCCESS' ,
            'total_amount' => 0.01,
        ]);

        $this->assertEquals('success', $this->getContent());
    }

}