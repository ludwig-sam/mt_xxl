<?php namespace App\DataTypes;


use App\DataTypes\MessageSendTypes;
use App\Exceptions\MessageSendException;
use App\Http\Codes\Code;

class MessageSendLogTypes {

    const status_pending = 'pending';
    const status_success = 'success';
    const status_fail  = 'fail';

    public static function getType()
    {
        return [
            MessageSendTypes::type_customer,
            MessageSendTypes::type_mass,
            MessageSendTypes::type_sms,
            MessageSendTypes::type_template
        ];
    }

    public static function getStatus()
    {
        return [
            self::status_fail,
            self::status_pending,
            self::status_success
        ];
    }

    public static function checkType($type){
        if(!in_array($type, self::getType())){
            throw new MessageSendException("发送方式错误", Code::message_method_not_exists);
        }
    }


}

