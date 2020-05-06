<?php

return [
    'notify_key'      => env('notify_key'),
    'money_transfrom' => [
        'wechat' => [
            'pay'                => [
                ['amount']
            ],
            'callbackConversion' => [
                [],
                ['total_fee', 'amount']
            ],
            'refund'             => [
                ['amount', 'refund_amount'],
                ['total_fee']
            ]
        ],
        'upay'   => [

        ]
    ]
];
