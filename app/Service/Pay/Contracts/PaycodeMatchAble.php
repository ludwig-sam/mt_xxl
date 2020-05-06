<?php

namespace App\Service\Pay\Contracts;


interface  PaycodeMatchAble
{
    public function addPrefix($code);

    public function removePrefix($code);

    public function isMe($code);
}