<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Libs\Log;

class SmsException extends ExceptionCustomCodeAble
{

    public function __construct(string $message = "", string $code = "", array $row = [])
    {
        Log::warning("auth_fail", [
            'code'  => $code,
            'msg'   => $message,
            'row'   => $row,
        ]);
        parent::__construct($message, $code, $row);
    }
}
