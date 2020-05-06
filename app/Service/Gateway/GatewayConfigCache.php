<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway;

use Illuminate\Support\Facades\Redis;


class GatewayConfigCache extends \App\Service\Gateway\Contracts\GatewayConfigAbstricts
{

    private $config;

    private $key = 'mt_2018_gateway';

    public function config()
    {
        return $this->config ? : [];
    }

    public function reloadConfig()
    {
        $this->config = Redis::hgetall($this->key);
    }

    public function set($field, $value)
    {
        return Redis::hset($this->key, $field, $value);
    }

    public function ipBlackList($value)
    {
        return json_decode($value, true);
    }
}