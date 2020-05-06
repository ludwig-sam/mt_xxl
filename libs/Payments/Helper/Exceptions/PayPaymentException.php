<?php

namespace Libs\Payments\Helper\Exceptions;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;

class PayPaymentException extends ExceptionCustomCodeAble
{

    const invalid_mode = 'invalid_mode';
    const miss_key     = 'miss_key';

    public function __construct(string $message = "", $code = "", Array $row = [])
    {
        parent::__construct($message, $code, $row);
    }
}
