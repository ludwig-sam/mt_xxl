<?php namespace App\DataTypes;


use App\DataTypes\MessageSendRoots;
use App\Models\Traits\ToArrayTrait;
use App\Service\MessageSend\Helper\TemplateHelper;

class MessagePlaceholders
{

    use ToArrayTrait;

    const date_at               = 'date_at';
    const pay_notify_payment_at = 'payment_at';
    const pay_notify_amount     = 'amount';
    const pay_notify_order_no   = 'order_no';
    const pay_today_total       = 'today_total';
    const pay_today_num         = 'today_num';
    const pay_store_name        = 'store_name';

    const refund_notify_refund_amount = 'refund_amount';
    const refund_notify_refund_reason = 'reason';

    const point_exchange_gift_notify_name      = 'name';
    const point_exchange_gift_notify_point     = 'point';
    const point_exchange_gift_notify_old_point = 'old_point ';
    const point_exchange_gift_notify_consume   = 'consume';

    const become_member_notify_registe_at = 'registe_at';
    const become_member_notify_nickname   = 'nickname';
    const become_member_notify_point      = 'point';

    const consume_notify_card_title = 'title';

    const member_level_notify_old_level = 'old_level';
    const member_level_notify_level     = 'level';


    const placeholders = [
        MessageSendRoots::pay_notify                 => [
            [
                'key'    => self::pay_notify_payment_at,
                'remark' => "支付时间",
            ],
            [
                "key"    => self::pay_notify_amount,
                "remark" => "支付金额"
            ],
            [
                "key"    => self::pay_notify_order_no,
                "remark" => "订单编号"
            ],
            [
                "key"    => self::pay_today_total,
                "remark" => "今日总计"
            ],
            [
                "key"    => self::pay_today_num,
                "remark" => "今日单数"
            ],
            [
                'key'    => self::pay_store_name,
                'remark' => '消费门店'
            ]
        ],
        MessageSendRoots::refund_notify              => [
            [
                "key"    => self::refund_notify_refund_amount,
                "remark" => "退款金额"
            ],
            [
                "key"    => self::refund_notify_refund_reason,
                "remark" => "退款原因"
            ]
        ],
        MessageSendRoots::refund_success_notify      => [
            [
                "key"    => self::refund_notify_refund_amount,
                "remark" => "退款金额"
            ],
            [
                "key"    => self::refund_notify_refund_reason,
                "remark" => "退款原因"
            ]
        ],
        MessageSendRoots::point_exchange_gift_notify => [
            [
                "key"    => self::date_at,
                "remark" => "时间"
            ],
            [
                "key"    => self::point_exchange_gift_notify_name,
                "remark" => "礼品名称"
            ],
            [
                "key"    => self::point_exchange_gift_notify_old_point,
                "remark" => "原始积分"
            ],
            [
                "key"    => self::point_exchange_gift_notify_consume,
                "remark" => "消耗积分"
            ],
            [
                "key"    => self::point_exchange_gift_notify_point,
                "remark" => "剩余积分"
            ]
        ],
        MessageSendRoots::become_member_notify       => [
            [
                "key"    => self::date_at,
                "remark" => "时间"
            ],
            [
                "key"    => self::become_member_notify_registe_at,
                "remark" => "注册时间"
            ],
            [
                'key'    => self::become_member_notify_point,
                "remark" => "会员积分"
            ],
            [
                'key'    => self::become_member_notify_nickname,
                "remark" => "会员昵称"
            ]
        ],
        MessageSendRoots::consume_notify             => [
            [
                "key"    => self::date_at,
                "remark" => "时间"
            ],
            [
                "key"    => self::consume_notify_card_title,
                "remark" => "券名"
            ]
        ],
        MessageSendRoots::member_level_notify        => [
            [
                "key"    => self::date_at,
                "remark" => "时间"
            ],
            [
                "key"    => self::member_level_notify_old_level,
                "remark" => "原先等级"
            ],
            [
                "key"    => self::member_level_notify_level,
                "remark" => "当前等级"
            ]
        ],
        MessageSendRoots::pay_success_notify         => [
            [
                "key"    => self::date_at,
                "remark" => "时间"
            ],
            [
                "key"    => self::pay_notify_order_no,
                "remark" => "订单编号"
            ],
            [
                "key"    => self::pay_notify_amount,
                "remark" => "支付金额"
            ],
            [
                "key"    => self::pay_today_total,
                "remark" => "今日总计"
            ],
            [
                "key"    => self::pay_today_num,
                "remark" => "今日单数"
            ],
            [
                'key'    => self::pay_store_name,
                'remark' => '消费门店'
            ]
        ]
    ];

    static function getPlaceholder($name)
    {
        if (!isset(self::placeholders[$name])) {
            return [];
        }

        $placeholders = self::placeholders[$name];

        foreach ($placeholders as &$placeholder) {
            $placeholder['value'] = TemplateHelper::getCurePlaceholder($placeholder['key']);
        }

        return $placeholders;
    }

}

