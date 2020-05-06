<?php namespace App\Service\Pay\Helpers;

use Libs\Str;

class PayMethod{

    private static $way;
    private static $type;


    public static function parseByPayment($payment){
        $className        = $payment['class_name'];
        self::$way        = Str::first($className, '.');
        self::$type       = Str::last($className, '.');
    }

    public static function getWay(){
        return self::$way;
    }

    public static function getType(){
        return self::$type;
    }


}