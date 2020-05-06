<?php namespace App\Service\Pay;


use App\Service\Mch\Mch;
use App\Service\Row\MethodRow;
use App\Service\Row\OrderRow;

class PaymentConfigFromOrder
{

    private $method;
    private $config;

    public function __construct(OrderRow $order)
    {
        $method       = new MethodRow($order->paymentId());
        $mch_service  = new Mch();
        $this->method = $method;
        $this->config = $mch_service->getPayConfig($order->mchId(), $method->uCWay());
    }

    public function config()
    {
        return $this->config;
    }

    public function method()
    {
        return $this->method;
    }

}

