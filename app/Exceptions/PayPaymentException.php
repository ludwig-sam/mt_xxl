<?php

namespace App\Exceptions;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\PayCode;

class PayPaymentException extends ExceptionCustomCodeAble
{

    const create_order_fail = PayCode::create_order_fail;
    const mch_payconfig_err = PayCode::mch_payconfig_err;
    const miss_payment_method = PayCode::miss_payment_method;
    const payment_mehtod_not_exists = PayCode::payment_mehtod_not_exists;
    const payment_mehtod_disable = PayCode::payment_mehtod_disable;
    const order_not_exists = PayCode::order_not_exists;
    const update_fail = PayCode::update_fail;
    const invalid_money = PayCode::invalid_money;
    const refund_success = PayCode::refund_success;
    const refund_status_err = PayCode::refund_status_err;
    const refund_fail = PayCode::refund_fail;
    const invalid_code = PayCode::invalid_code;
    const invalid_mode = PayCode::invalid_mode;
    const miss_key  = PayCode::miss_key;
    const undefined_way          = PayCode::undefined_way;
    const invalid_auth_code      = PayCode::invalid_auth_code;
    const undefined_payment_id   = PayCode::undefined_payment_id;
}
