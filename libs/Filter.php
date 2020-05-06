<?php namespace Libs;


class Filter{
    const str    = 'string';
    const int    = 'int';
    const float  = 'float';
    const email  = 'email';
    const bool   = 'bool';
    const url    = 'url';


    static function string($var){
        return self::act($var, self::str);
    }

    static function int($var){
        return self::act($var, self::int);
    }

    static function float($var){
        return self::act($var, self::float);
    }

    static function email($var){
        return self::act($var, self::email);
    }

    static function url($var){
        return self::act($var, self::url);
    }

    static function act($var, $type){

        switch ((string)$type){
            case self::bool:
                $value = (boolean)$var;
                break;
            case self::str:
                $value = filter_var($var, FILTER_SANITIZE_STRING);
                break;
            case self::int:
                $value = intval($var);
                break;
            case self::float:
                $value = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                break;
            case self::url:
                $value = filter_var($var, FILTER_SANITIZE_URL);
                break;
            case self::email:
                $value = filter_var($var, FILTER_SANITIZE_EMAIL);
                break;
            default:
                $value = filter_var($var, FILTER_SANITIZE_SPECIAL_CHARS);
                break;
        }
        return $value;
    }

}


