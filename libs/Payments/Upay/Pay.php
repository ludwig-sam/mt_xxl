<?php

namespace Libs\Payments\Upay;

use Libs\Arr;
use Libs\Payments\Contracts\Config;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;
use Libs\Payments\Upay\Support\Support;

abstract class Pay implements PayableInterface
{

    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract function prePay(Array $params);

    public function refund(Collection $order)
    {
    }

    public function pay(Array $payload, Collection $params):Collection
    {
        $params     = $params->merge($payload);
        $params     = toArray($params);
        $bizContent = $this->prePay($params);
        $params     = Arr::getIfExists($params, $this->getCommonFields());

        $params['biz_content'] = json_encode($bizContent, JSON_UNESCAPED_UNICODE);
        $params['biz_channel'] = $this->getChannel();
        $params['biz_type']    = $this->getTradeType();

        $params['sign'] = Support::generateSign($params, $this->config->getKey());

        $result = Support::requestApi('', $params, $this->config);

        $bzContent = json_decode($result->get('biz_content'), true);
        $bzContent = new Collection($bzContent);

        $return = [
            "amount"         => $bzContent->get('total_amount'),
            "order_no"       => $bzContent->get('ext_no'),
            "transaction_id" => $bzContent->get('trade_no'),
            "wx_appid"       => '',
            "openid"         => $bzContent->get('buyer_id'),
            "attach"         => $bzContent->get('attach'),
            'qr_code'        => $bzContent->get('qr_code')
        ];

        return $this->after(new Collection($return));
    }

    public function verify(Collection $params)
    {
    }

    protected function getCommonFields()
    {
        return [
            'merchant_id',
            'terminal_id',
            'operator_id',
            'device_id',
            'request_id',
            'term_request_id',
            'timestamp',
            'biz_channel',
            'biz_type',
            'biz_content',
            'notify_url',
            'version',
            'app_info',
            'sign',
            'sign_type',
            'sign_format'
        ];
    }

    function after(Collection $result):Collection
    {
        return $result;
    }


}