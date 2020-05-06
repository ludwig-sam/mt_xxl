<?php

namespace Libs\Payments\Helper;

use Illuminate\Support\Collection;
use Libs\Obj;
use Libs\Payments\Contracts\PaymentApplicationInterface;

class PayProxy implements PaymentApplicationInterface
{

    private $paymentApplication;
    private $name;

    public function __construct(PaymentApplicationInterface $paymentApplication)
    {
        $this->paymentApplication = $paymentApplication;
        $this->name               = strtolower(Obj::name($paymentApplication));
    }

    private function action($method)
    {
        return $this->name . '.' . $method;
    }

    public function cancel($gateway, $order)
    {
        return $this->paymentApplication->close($gateway, $order);
    }

    public function close($gateway, $order)
    {
        return $this->paymentApplication->close($gateway, $order);
    }

    public function find($gateway, Collection $order):Collection
    {
        return $this->paymentApplication->find($gateway, $order);
    }

    public function pay($gateway, Collection $params):Collection
    {
        $name = $this->action(__FUNCTION__);

        MoneyFilter::before($name, $params);

        $result = $this->paymentApplication->pay($gateway, $params);

        MoneyFilter::after($name, $result);

        return $result;
    }

    public function refund($gateway, Collection $order):Collection
    {
        $name = $this->action(__FUNCTION__);

        MoneyFilter::before($name, $order);

        $result = $this->paymentApplication->refund($gateway, $order);

        MoneyFilter::after($name, $result);

        return $result;
    }

    public function success()
    {
        return $this->paymentApplication->success();
    }

    public function verify():Collection
    {
        return $this->paymentApplication->verify();
    }

    public function callbackConversion($data):Collection
    {
        $name = $this->action(__FUNCTION__);

        $data = $this->paymentApplication->callbackConversion($data);

        $result = new Collection($data);

        MoneyFilter::after($name, $result);

        return $result;
    }
}