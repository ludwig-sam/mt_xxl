<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\CardCodeController;

class CodeRule implements RuleInterface {
    public  function rule(){
        return [
            CardCodeController::class => [
                'isScan' => [
                    'code' => 'required',
                ]
            ]
        ];
    }
}