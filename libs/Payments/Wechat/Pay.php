<?php

namespace Libs\Payments\Wechat;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Libs\Log;
use Libs\Payments\Contracts\PayableInterface;
use Libs\Payments\Wechat\Support\Config;
use Libs\Payments\Wechat\Support\Support;
use Libs\Time;

abstract class Pay implements PayableInterface
{

    protected $config;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function refund(Collection $order)
    {
    }

    private function setCommonParam(Array $payload, Collection $params)
    {
        $payload['trade_type']       = $this->getTradeType();
        $payload['body']             = $params->get('subject', '商品');
        $payload['out_trade_no']     = $params->get('order_no');
        $payload['total_fee']        = $params->get('amount');
        $payload['openid']           = $params->get('openid');
        $payload['time_expire']      = Time::formatReset('YmdHis', $params->get('expired_at'));
        $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');

        $sign = Support::generateSign($payload, $this->config->getKey());

        $payload['sign'] = $sign;

        return $payload;
    }

    public function verify(Collection $params)
    {
    }

    function pay(Array $payload, Collection $params):Collection
    {
        $payload = $this->preOrder($payload, $params);
        $payload = $this->setCommonParam($payload, $params);

        Log::debug('Pay Order wechat:', [$this->endPoint(), $payload]);

        $result = Support::requestApi($this->endPoint(), $payload, $this->config);

        return $this->after($result);
    }

    function getChannel()
    {
        return '';
    }

    function after(Collection $result):Collection
    {
        return $result;
    }

    abstract function preOrder(Array $payload, Collection $params);

    abstract function endPoint();

}
