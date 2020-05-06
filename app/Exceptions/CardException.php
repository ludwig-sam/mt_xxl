<?php

namespace App\Exceptions;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;

class CardException extends ExceptionCustomCodeAble
{
    const cant_use = 10001;
    const disabled = 10002;
    const card_not_exists = 10003;
}
