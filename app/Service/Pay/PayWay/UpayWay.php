<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/10
 * Time: 下午9:02
 */

namespace App\Service\Pay\PayWay;


use App\Exceptions\PayConfigException;
use App\PayConfig;
use App\Service\Pay\Contracts\PayWayAbstracts;
use Illuminate\Support\Collection;

class UpayWay extends PayWayAbstracts
{
    public function way()
    {
        return PayConfig::way_upay;
    }

    public function param(Collection $collection)
    {
        $merchant_id = $collection->get('merchant_id');
        $terminal_id = $collection->get('terminal_id');
        $key         = $collection->get('key');

        if(!$merchant_id){
            throw new PayConfigException("请填写银联appid");
        }

        if(!$terminal_id){
            throw new PayConfigException("请填写设备号");
        }

        if(!$key){
            throw new PayConfigException("请填写key");
        }

        return [
            'terminal_id' => $terminal_id,
            'merchant_id' => $merchant_id,
            'key'         => $key,
        ];
    }
}