<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\MemberController;

class Member implements RuleInterface {
    public  function rule(){
        return [
            MemberController::class => [
                'update' => [
                    'id'      => 'required'
                ],
                'sendCard' => [
                    "way"           => "required",
                    "member_id"     => "array",
                    "wx_card_id"    => "required",
                ]
            ]
        ];
    }
}