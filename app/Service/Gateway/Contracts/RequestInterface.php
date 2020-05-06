<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:32
 */

namespace App\Service\Gateway\Contracts;


interface RequestInterface
{
    function getIp();

    function getRouteSplit();

    function getBodyContent();
}