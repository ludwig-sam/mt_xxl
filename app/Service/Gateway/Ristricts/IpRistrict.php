<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway\Ristricts;


use App\Service\Gateway\Contracts\GatewayConfigAbstricts;
use App\Service\Gateway\Contracts\RequestInterface;
use App\Service\Gateway\Contracts\RistrictInterface;
use App\Service\Gateway\GatewayConfigCache;

class IpRistrict implements RistrictInterface
{

    private $ip;

    public function __construct(RequestInterface $ip)
    {
        $this->ip = $ip;
    }

    public function isPass()
    {
        return !in_array($this->ip->getIp(), $this->getBlackList());
    }

    public function getIp()
    {
        return $this->ip->getIp();
    }

    private function config() : GatewayConfigAbstricts
    {
        return new GatewayConfigCache();
    }

    public function getBlackList()
    {
        return $this->config()->getBlackIpList([]);
    }
}