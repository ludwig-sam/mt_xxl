<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\BankCardController;
use App\Http\Controllers\Mchs\WithdrawController;

class Withdraw implements RuleInterface
{
    public function rule()
    {
        return [
            BankCardController::class => [
                'create' => [
                    'card_number' => 'required|max:30',
                    'bank_name'   => 'required|max:60',
                    'name'        => 'max:45',
                    'mobile'      => 'required'
                ]
            ],
            WithdrawController::class => [
                'create' => [
                    'apply_money'  => 'required|numeric|max:9999999999',
                    'bank_card_id' => 'required|integer',
                ],
                'update' => [
                    'apply_money' => 'numeric|max:9999999999'
                ]
            ]
        ];
    }
}