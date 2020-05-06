<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\Code;

class MemberException  extends ExceptionCustomCodeAble
{
    const member_not_exists   = Code::method_not_allowed_http_exception;
    const invalid_member_code = Code::invalid_member_code;
    const update_member_fail  = Code::update_member_fail;
    const member_create_fail  = Code::member_create_fail;
}
