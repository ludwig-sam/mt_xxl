<?php namespace App\Http;

use Abstracts\RuleInterface;


class Rules{
    static private $rules = [];

    public static function registe(RuleInterface $rule){
        if(!is_null($rule))
        self::$rules = array_merge(self::$rules, $rule->rule());
    }

    public static function rule(){
        return self::$rules ;
    }

}

