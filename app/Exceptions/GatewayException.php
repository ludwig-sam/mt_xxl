<?php

namespace App\Exceptions;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Service\Gateway\NoteService;
use App\Service\Gateway\RequestService;

class GatewayException extends ExceptionCustomCodeAble
{
    public function __construct(string $message = "", string $code = "", array $row = [])
    {

        $note_service = new NoteService(new RequestService());

        $note_service->attach($row);

        parent::__construct($message, $code, $row);
    }
}
