<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\MchController;

class Mch implements RuleInterface {
    public  function rule(){
        return [
            MchController::class => [
                'update' => [
//                    'banner' => ''
                ]
            ]
        ];
    }
}