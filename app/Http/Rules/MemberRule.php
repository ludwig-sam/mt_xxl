<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\WechatCardController;


class MemberRule implements RuleInterface {
    public  function rule(){
        return [
            WechatCardController::class => [
                'memberActivate' => [
                    'mobile' => 'required'
                ]
            ]
        ];
    }
}