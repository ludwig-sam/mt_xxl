<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\MaterialController;


class MaterialRule implements RuleInterface {
    public  function rule(){
        return [
            MaterialController::class => [

            ]
        ];
    }
}