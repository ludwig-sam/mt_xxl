<?php

namespace Libs\Payments\Wechat;

use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;
use Libs\Payments\Wechat\Support\JsApi;
use Libs\Str;

class MinipayPayment extends Pay implements PayableInterface
{

    function preOrder(Array $payload, Collection $params)
    {
        return $payload;
    }

    function after(Collection $result):Collection
    {
        $prepay_id    = $result->get('prepay_id');
        $jsApi        = new JsApi();
        $js_api_param = [
            'appId'     => $this->config->getAppId(),
            'timeStamp' => (string)time(),
            'nonceStr'  => Str::rand(20),
            'package'   => "prepay_id={$prepay_id}",
            'signType'  => 'MD5'
        ];

        $js_api_param['paySign'] = $jsApi->sign($js_api_param, $this->config->getKey());

        return new Collection(compact('prepay_id', 'js_api_param'));
    }

    function getTradeType()
    {
        return 'JSAPI';
    }

    public function endPoint()
    {
        return 'pay/unifiedorder';
    }

}