<?php

namespace Libs\Payments\Contracts;

use Illuminate\Support\Collection;

interface PayableInterface
{
    public function pay(Array $payload, Collection $params):Collection;

    public function verify(Collection $params);

    public function refund(Collection $order);

    function getChannel();

    function getTradeType();

}

