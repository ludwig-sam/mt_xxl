<?php namespace App\Http\Controllers\Receive;

use App\Service\Pay\PaymentConfigFromOrder;
use App\Service\Row\OrderRow;
use Illuminate\Support\Collection;
use Libs\Pay;
use App\Service\Pay\Payment;
use Illuminate\Http\Request;


class PaymentController extends BaseController
{


    public function getStatus(Request $request)
    {
        $order_id = $request->get('order_id');
        $order    = new OrderRow($order_id);
        $config   = new PaymentConfigFromOrder($order);
        $method   = $config->method();
        $param    = new Collection([
            'order_no' => $order->orderNo()
        ]);

        $result = Pay::payment($method->channel(), $config->config())->find(Payment::mook() . $method->tradeType(), $param);

        return json_decode($result->get('biz_content'), true);
    }

}