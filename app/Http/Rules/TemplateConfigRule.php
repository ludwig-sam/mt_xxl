<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;


class TemplateConfigRule implements RuleInterface {
    public  function rule(){
        return [
            TemplateConfigRule::class => [
                'tempalteInit' => [
                    'name' => 'required'
                ]
            ]
        ];
    }
}