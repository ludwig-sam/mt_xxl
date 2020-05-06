<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\PayNotifyController;


class PayNotifyRule implements RuleInterface {
    public  function rule(){
        return [
            PayNotifyController::class => [
                'addNotifyUser' => [
                    'openid' => 'required'
                ]
            ]
        ];
    }
}