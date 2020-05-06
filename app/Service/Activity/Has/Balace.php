<?php

namespace App\Service\Activity\Has;


use App\Exceptions\MemberException;
use App\Service\Activity\Contracts\ExchangeAble;
use App\Service\Users\Contracts\UserAbstraict;

class Balace implements ExchangeAble
{

    public function check ($info, UserAbstraict &$user)
    {
        throw new MemberException("暂不支持类型余额兑换");
    }

    public function exchange($info, UserAbstraict &$user)
    {
    }
}