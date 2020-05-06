<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Libs\Log;

class AuthException extends ExceptionCustomCodeAble
{

    public function __construct(string $message = "", string $code = "", array $row = [])
    {
        Log::warning("auth_fail", [
            'header'    => request()->headers->all(),
            'reqeust'   => request()->all()
        ]);
        parent::__construct($message, $code, $row);
    }
}
