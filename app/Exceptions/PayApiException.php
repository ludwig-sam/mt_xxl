<?php

namespace App\Exceptions;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\PayCode;

class PayApiException extends ExceptionCustomCodeAble
{
    const api_error                 = PayCode::api_error;
    const api_message               = PayCode::api_message;
    const pay_gateway_undefind      = PayCode::pay_gateway_undefind;
    const pay_method_undefind       = PayCode::pay_method_undefind;
    const pay_gateway_not_instance  = PayCode::pay_gateway_not_instance;
    const sys_err                   = PayCode::sys_err;
    const data_convert_fail         = PayCode::data_convert_fail;
    const pay_api_invalid_sign      = PayCode::pay_api_invalid_sign;
    const unknow_param_value        = PayCode::unknow_param_value;

}
