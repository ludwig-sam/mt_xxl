<?php namespace App\DataTypes;


class WithdrawTypes
{

    const status_peding  = 'PENDING';
    const status_success = 'SUCCESS';
    const status_refuse  = 'REFUSE';

    public static function statusToName($status)
    {
        switch ($status) {
            case self::status_success:
                return '完成';
                break;
            case self::status_refuse:
                return '拒绝';
                break;
            default:
                return '处理中';
                break;
        }
    }
}

