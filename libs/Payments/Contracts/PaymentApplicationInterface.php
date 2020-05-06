<?php

namespace Libs\Payments\Contracts;

use Illuminate\Support\Collection;

interface PaymentApplicationInterface
{
    public function pay($trade_type, Collection $params):Collection;

    public function find($trade_type, Collection $order):Collection;

    public function refund($trade_type, Collection $order):Collection;

    public function cancel($trade_type, $order);

    public function close($trade_type, $order);

    public function verify():Collection;

    public function success();

    public function callbackConversion($data):Collection;
}

