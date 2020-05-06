<?php

namespace App\Exceptions;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Throwable;

class PayMchException extends ExceptionCustomCodeAble
{

    const not_exists    = 1001;
    const user_name_err = 1002;
    const password_err  = 1003;
    const cashier_disable  = 1004;
}
