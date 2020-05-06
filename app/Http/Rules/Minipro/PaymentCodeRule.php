<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\PaymentCodeShowController;

class PaymentCodeRule implements RuleInterface
{
    public function rule()
    {
        return [
            PaymentCodeShowController::class => [
                'qrcode' => [
                    'code' => 'required'
                ]
            ]
        ];
    }
}