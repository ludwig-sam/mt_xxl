<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午3:40
 */

namespace Libs\Payments\Special;


use App\DataTypes\PayOrderStatus;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;
use Libs\Payments\Special\Support\Support;

class CashPayment extends Pay implements PayableInterface
{

    public function getChannel()
    {
    }

    public function getTradeType()
    {
    }

    public function pay(Array $payload, Collection $params):Collection
    {

        $order_id = $params->get('id');
        Support::paySuccess($order_id);

        //        Support::sendPayNotify($order->get('notify_url'), $order);

        return new Collection([
            'status' => PayOrderStatus::PAY_STATUS_SUCCES
        ]);
    }

}