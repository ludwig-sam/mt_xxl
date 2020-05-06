<?php

namespace Libs\Payments\Upay;


use Libs\Payments\Contracts\PayableInterface;

class MookMicroWechatPayment extends Pay implements PayableInterface
{


    public function getChannel()
    {
    }

    public function getTradeType()
    {
    }

    public function prePay(Array $params)
    {
        return [
            "amount"         => 1,
            "order_no"       => 'order mook',
            "transaction_id" => 'transid',
            "wx_appid"       => '',
            "openid"         => 'openid',
            "attach"         => 'attach',
        ];
    }
}