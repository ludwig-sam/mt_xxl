<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\MemberAccountConfigController;

class MemberConfig implements RuleInterface
{
    public function rule()
    {
        return [
            MemberAccountConfigController::class => [
                'updatePayPwd' => [
                    'pay_password' => 'required|min:6'
                ]
            ]
        ];
    }
}