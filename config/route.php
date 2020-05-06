<?php

return [
    'api' => [
        'adauth'    => [
            'want_json'
        ],
        'mt'        => [
            'want_json', 'wateway_risk', 'sign'
        ],
        'pub'       => [
            'want_json', 'log_request'
        ],
        'dpd'       => [
            'want_json'
        ],
        'h5pay'     => [
            'want_json', 'log_request'
        ],
        'pay'       => [
            'want_json', 'log_request', 'verify_access_token', 'pay'
        ],
        'mini_auth' => [
            'want_json'
        ],
        'minipro'   => [
            'want_json', 'log_request'
        ],
        'admin'     => [
            'want_json', 'log_request'
        ],
        'mch'       => [
            'want_json', 'log_request'
        ]
    ]
];