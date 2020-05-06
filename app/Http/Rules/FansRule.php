<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\FansController;


class FansRule implements RuleInterface {
    public  function rule(){
        return [
            FansController::class => [
                'updateByOpenid' => [
                    'openid' => 'required'
                ]
            ]
        ];
    }
}