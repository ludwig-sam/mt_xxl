<?php namespace App\Http\Codes;


class Code{

    const success      = 'success';
    const login_expire = 'login_expire';
    const user_not_found = 'user_not_found';
    const  token_expired = 'token_expired';
    const  token_invalid = 'token_invalid';
    const  token_absent = 'token_absent';
    const  invalid_credentials = 'invalid_credentials';
    const could_not_create_token = 'could_not_create_token';
    const create_user_fail = 'create_user_fail';
    const validate_fail = 'validate_fail';
    const create_fial   = 'create_fial';
    const sys_err       = 'sys_err';
    const delete_fail   = 'delete_fail';
    const fail          = 'fail';
    const invalid_param = 'invalid_param';
    const update_fail = 'update_fail';



    //exception
    const http_exception = 'http_exception';
    const fatal_throwable_error = 'fatal_throwable_error';
    const method_not_allowed_http_exception = 'method_not_allowed_http_exception';
    const exception         = 'exception';

    const not_exists = 'not_exists';

    //upload
    const upload_fail = 'upload_fail';

    //card
    const decrypt_code_fall = 'decrypt_code_fall';
    const activate_fail = 'activate_fail';
    const wx_activate_fail = 'wx_activate_fail';
    const card_disabled = 'card_disabled';
    const wx_decode_fail = 'wx_decode_fail';
    const card_not_exists = 'wx_card_not_exists';
    const wechat_error = 'wechat_error';
    const has_received = 'has_received';

    //permission

    const  permission_denied = 'permission_denied';


    //member

    const member_not_exists   = 10001;
    const invalid_member_code = 10002;
    const update_member_fail  = 10003;
    const member_create_fail  = 10004;
    const registe_reward_not_exist  = 'registe_reward_not_exist';

    //message_send

    const message_method_not_exists = 'message_method_not_exists';

    const pay_success_consume_card_fail = 'pay_success_consume_card_fail';

    //sms

    const invalid_sms_verify_code = 'invalid_sms_verify_code';
    const sms_send_fail = 'sms_send_fail';

    //role
    const role_not_exists = 'role_not_exists';

    //mch
    const mch_not_exists = 'mch_not_exists';

    //gateway
    const gateway_not_pass = 'gateway_not_pass';

}