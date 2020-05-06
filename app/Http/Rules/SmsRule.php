<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Pub\SmsController;


class SmsRule implements RuleInterface {
    public  function rule(){
        return [
            SmsController::class => [
                'verify' => [
                    'mobile' => 'required',
                    'sms_code'   => 'required',
                ]
            ],
            \App\Http\Controllers\Minipro\SmsController::class => [
                'send' => [
                    'mobile' => 'required|regex:/^1\d{10}$/'
                ]
            ]
        ];
    }
}