<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Libs\Log;

class ReplyException extends ExceptionCustomCodeAble
{

    public function __construct(string $message = "", string $code = "", array $row = [])
    {

        Log::warning($message, $row);

        parent::__construct($message, $code, $row);
    }

}
