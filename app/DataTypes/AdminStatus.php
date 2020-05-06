<?php namespace App\DataTypes;


use App\Exceptions\AuthException;

class AdminStatus {

    const status_normal = 'NOMAL';
    const status_lock   = 'LOCK';
    const status_not_active   = 'NOT_ACTIVE';

    static function checkLogin($status)
    {
        if($status != self::status_normal){
            throw new AuthException("操作员状态不正常");
        }
    }

}

