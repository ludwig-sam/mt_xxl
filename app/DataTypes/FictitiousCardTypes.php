<?php namespace App\DataTypes;


class FictitiousCardTypes
{
    const status_disabled     = 'disabled';
    const status_normal       = 'normal';
    const date_type_range     = 'range';
    const date_type_permanent = 'permanent';

    const code_status_used  = 'used';
    const code_status_grant = 'grant';

    static function statusToName($status)
    {
        switch ($status) {
            case self::code_status_used:
                return '已使用';
                break;
            default:
                return '发放中';
                break;
        }
    }
}

