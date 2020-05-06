<?php


return [
    'system_event_recharge' => [
        [
            'name'          => 'recharge_success',
            'is_async'      => 0,
            'delay'         => 0,
            'condition_op'  => 'equal',
            'condition_key' => 'status',
            'condition_val' => \App\DataTypes\RecharegeStatus::success,
        ]
    ]
];
