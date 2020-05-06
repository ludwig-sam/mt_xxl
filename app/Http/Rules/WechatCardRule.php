<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Pay\CardController;


class WechatCardRule implements RuleInterface {
    public  function rule(){
        return [
            CardController::class => [
                'getWxCardInfo' => [
                    'code' => 'required'
                ],
                'getMemberCard' => [
                    'code' => 'required'
                ],
                'consume' => [
                    'card_code' => 'required',
                    'card_id'   => 'required',
                ],
                'scanCode' => [
                    'code' => 'required'
                ]
            ]
        ];
    }
}