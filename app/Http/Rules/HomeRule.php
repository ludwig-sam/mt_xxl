<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Web\HomeController;


class HomeRule implements RuleInterface {
    public  function rule(){
        return [
            HomeController::class => [
                "index" => [
                    "before_day" => 'Integer'
                ]
            ]
        ];
    }
}