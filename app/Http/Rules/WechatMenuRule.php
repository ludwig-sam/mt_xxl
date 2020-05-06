<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\WechatMenuController;
use App\Http\Controllers\Minipro\WechatCardController;


class WechatMenuRule implements RuleInterface {
    public  function rule(){
        return [
            WechatMenuController::class => [
                'add' => [
                    'name' => 'required',
                    "type" => "required",
                    "pid"  => "required|Integer",
                    "condition_id" => 'Integer'
                ],
                "update" => [
                    'name' => 'required',
                    "type" => "required",
                ],
                "sort" => [
                    "sort" => "required|Integer",
                ]
            ]
        ];
    }
}