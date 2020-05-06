<?php namespace App\Http\Codes;


class PayCode{


    const token_expire      = 10001;
    const miss_access_token = 10002;

    const undefined_way        = 10003;
    const invalid_auth_code    = 10004;
    const undefined_payment_id = 10005;

    const not_exists       = 10006;
    const user_name_err    = 10007;
    const password_err     = 10008;
    const cashier_disable  = 10009;

    const create_order_fail = 20001;
    const mch_payconfig_err = 20002;
    const miss_payment_method       = 20003;
    const payment_mehtod_not_exists = 20004;
    const payment_mehtod_disable    = 20005;
    const order_not_exists          = 20006;
    const update_fail       = 20007;
    const invalid_money     = 20008;
    const refund_success    = 20009;
    const refund_status_err = 30001;
    const refund_fail       = 30002;
    const invalid_code      = 30003;


    const api_error                 = 30004;
    const api_message               = 30005;
    const pay_gateway_undefind      = 30006;
    const pay_method_undefind       = 30007;
    const pay_gateway_not_instance  = 30008;
    const sys_err                   = 30009;

    const pay_api_invalid_sign    = 40001;
    const data_convert_fail  = 40002;
    const unknow_param_value = 40003;

    const invalid_mode = 40004;
    const miss_key  = 40005;
}