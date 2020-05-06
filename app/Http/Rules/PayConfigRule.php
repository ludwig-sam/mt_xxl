<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\PayConfigController;
use App\Http\Controllers\Pub\SmsController;


class PayConfigRule implements RuleInterface {
    public  function rule(){
        return [
            PayConfigController::class => [
                'update' => [
                    'way' => 'required'
                ]
            ]
        ];
    }
}