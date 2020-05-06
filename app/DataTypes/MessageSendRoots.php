<?php namespace App\DataTypes;


class MessageSendRoots
{

    const pay_notify = 'pay_notify';
    const pay_fail_notify = 'pay_fail_notify';
    const refund_notify = 'refund_notify';
    const pay_success_notify = 'pay_success_notify';
    const become_member_notify = 'become_member_notify';
    const point_exchange_gift_notify = 'point_exchange_gift_notify';
    const member_level_notify = 'member_level_notify';
    const member_exp_notify = 'member_exp_notify';
    const consume_notify = 'consume_notify';
    const refund_success_notify = 'refund_success_notify';

    static function checkRoots($root, $roots)
    {
        if(!in_array($root, $roots)){
            throw new \Exception("非特殊入口：" . $root);
        }
    }

    static function getRoots()
    {
        return [self::pay_notify, self::refund_notify, self::pay_fail_notify];
    }
}

