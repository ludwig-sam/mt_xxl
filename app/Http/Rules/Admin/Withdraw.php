<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\DataTypes\WithdrawTypes;
use App\Http\Controllers\Mchs\WithdrawController;
use Illuminate\Validation\Rules\In;

class Withdraw implements RuleInterface
{
    public function rule()
    {
        return [
            WithdrawController::class => [
                'update' => [
                    'status' => 'in:' . new In([WithdrawTypes::status_success, WithdrawTypes::status_refuse])
                ]
            ]
        ];
    }
}