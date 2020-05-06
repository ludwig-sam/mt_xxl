<?php namespace App\Http;

class Filter{

    const config = [
        'create_pay_order' => [
            [
                'total_amount','amount','refund_amount'
            ],
            [
                'total_amount', 'receipt_amount','amount','refund_amount'
            ]
        ],
        'refund_pay_order' => [
            [
                'refund_amount'
            ],
            [
                'refund_amount'
            ]
        ],
        'pay_pay_list' => [
            [

            ],
            [
                'total_amount','amount','refund_amount'
            ],
            'list'
        ],
        'pay_refund_list' => [
            [
            ],
            [
                'total_amount','amount','refund_amount'
            ],
            'list'
        ],
	    'show_pay_order' => [
	    	[

		    ],
		    [
			    'total_amount','amount','refund_amount'
		    ]
	    ],
        'show_refund_order' => [
	        [

	        ],
	        [
		        'refund_amount'
	        ],
        ],
        'pay_calculation' => [
            [ 'total_amount' ],
            [ 'total_amount', 'amount' ]
        ],
    ];
}