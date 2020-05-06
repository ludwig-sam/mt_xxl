<?php namespace App\Http\Rules\Pay;

use Abstracts\RuleInterface;
use App\Http\Controllers\Pay\CashierController;
use App\Http\Controllers\Pay\OrderController;

class PayRule implements RuleInterface
{

    public function rule()
    {
        return [
            OrderController::class => [
                'create' => [
                    'order_no' => 'required|unique:pay_order',
                    'total_amount' => 'required',
                ]
            ],
            CashierController::class => [
                'login' => [
                    'username' => 'required',
                    'password' => 'required',
                    'dev_no'   => 'required'
                ]
            ]
        ];
    }

}