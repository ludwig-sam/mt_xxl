<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\PayCode;
use Libs\Log;

class TokenException extends ExceptionCustomCodeAble
{
    const token_expire      = PayCode::token_expire;
    const miss_access_token = PayCode::miss_access_token;

    public function __construct(string $message = "", string $code = "", array $row = [])
    {
        Log::warning("token_fail", [
            'get'   => request()->getUri(),
            'code'  => $code,
            'msg'   => $message
        ]);

        parent::__construct($message, $code, $row);
    }
}
