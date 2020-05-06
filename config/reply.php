<?php


return [
    'event_user_consume_card' => [
        [
            'name'          => 'exe_consume_card_log',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => 'reg_exp',
            'condition_key' => 'OuterStr',
            'condition_val' => '/^outer_str_card_consume_exe_consume:/',
        ],
        [
            'name'          => 'consume_card',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ],
    'event_user_get_card'     => [
        [
            'name'          => 'exchange_success',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => 'reg_exp',
            'condition_key' => 'OuterStr',
            'condition_val' => '/^card_receive_exchange/',
        ],
        [
            'name'          => 'receive_card',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'register_reward',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => 'equal',
            'condition_key' => 'OuterStr',
            'condition_val' => \App\DataTypes\OutStrTypes::outer_str_registe_reward,
        ]
    ],
    'event_subscribe'         => [
        [
            'name'          => 'register_member',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ],
    'event_unsubscribe'       => [
        [
            'name'          => 'member_unsubscribe',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ],
    'event_mt_pay'            => [
        [
            'name'          => 'pay_success_send_card',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_send_message',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_member_level',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_mch',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_point',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_consume_card',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ],
        [
            'name'          => 'pay_success_consume_balance',
            'is_async'      => 1,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ],
    'event_mt_pay_fail'       => [
        [
            'name'          => 'pay_fail_send_message',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ],
    'event_mt_refund'         => [
        [
            'name'          => 'refund_send_message',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => null,
            'condition_key' => null,
            'condition_val' => null,
        ]
    ]
];
