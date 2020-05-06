<?php namespace App\DataTypes;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;

class ExchangeTypes{

    const exchange_name_point   = 'POINT';
    const exchange_name_balance = 'BALANCE';
    const exchange_name_exp     = 'EXP';


    static function getTypes()
    {
        return [self::exchange_name_point, self::exchange_name_balance, self::exchange_name_exp];
    }

    static function checkType($type)
    {
        if(!in_array($type, self::getTypes())){
            throw new ExceptionCustomCodeAble("无效的兑换方式：" . $type);
        }
    }
}

