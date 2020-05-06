<?php

namespace Libs\Payments\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;

class MicroPayment extends Pay implements PayableInterface
{

    function preOrder(Array $payload, Collection $params)
    {
        $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        return $payload;
    }

    function getTradeType()
    {
        return 'MICROPAY';
    }

    function endPoint()
    {
        return 'pay/micropay';
    }

}