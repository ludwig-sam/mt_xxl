<?php

namespace App\Service\Activity\Has;


use App\Exceptions\MemberException;
use App\Service\Activity\Contracts\ExchangeAble;
use App\Service\Users\Contracts\UserAbstraict;

class Def implements ExchangeAble
{

    public function check($info, UserAbstraict &$user)
    {
        throw new MemberException("不支持的兑换类型");
    }

    public function exchange($info, UserAbstraict &$user)
    {
    }
}