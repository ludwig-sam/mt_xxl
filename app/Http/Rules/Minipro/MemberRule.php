<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\MemberController;

class MemberRule implements RuleInterface {
    public  function rule(){
        return [
            MemberController::class => [
                'receiveCardInfo' => [
                    'encrypt_code' => 'required'
                ]
            ]
        ];
    }
}