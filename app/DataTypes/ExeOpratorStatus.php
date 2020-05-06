<?php namespace App\DataTypes;

class ExeOpratorStatus {

    const disabled = 'DISABLED';
    const normal   = 'NORMAL';

    public static function status(){
        return [self::disabled, self::normal];
    }
}

