<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午10:40
 */

namespace App\Service\Gateway\TestHelper;


class IpService extends \App\Service\Gateway\RequestService
{

    private $ip;
    private $route;

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setRoute($routes)
    {
        return $this->route = $routes;
    }

    public function getRouteSplit()
    {
        return $this->route;
    }
}