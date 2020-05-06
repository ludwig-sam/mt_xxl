<?php

namespace App\Service\Activity\Contracts;


use App\Service\Users\Contracts\UserAbstraict;

interface ExchangeAble
{
    function exchange($info, UserAbstraict &$user);

    function check($info, UserAbstraict &$user);
}