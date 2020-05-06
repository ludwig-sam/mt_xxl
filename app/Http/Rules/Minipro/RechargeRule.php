<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\RechargeController;

class RechargeRule implements RuleInterface
{
    public function rule()
    {
        return [
            RechargeController::class => [
                'recharge' => [
                    'method' => 'required',
                    'amount' => 'numeric',
                ]
            ]
        ];
    }
}