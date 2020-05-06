<?php

namespace App\Service\Wechat\Contracts;


interface MediaInterface
{
    function limit($type, $start, $number = 20);
}