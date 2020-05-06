<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/10
 * Time: 下午9:02
 */

namespace App\Service\Pay\PayWay;


use App\PayConfig;
use App\Service\Pay\Contracts\PayWayAbstracts;
use Illuminate\Support\Collection;

class ZxWay extends PayWayAbstracts
{

    public function way()
    {
        return PayConfig::way_zx;
    }

    public function param(Collection $collection)
    {

    }
}