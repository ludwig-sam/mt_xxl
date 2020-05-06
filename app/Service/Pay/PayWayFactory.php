<?php namespace App\Service\Pay;



use App\Exceptions\PayPaymentException;
use App\Service\Pay\Contracts\PayWayAbstracts;
use Illuminate\Support\Str;

class PayWayFactory {

    static function make($way) : PayWayAbstracts
    {
        $way   = strtolower($way);
        $class = __NAMESPACE__ . '\\PayWay\\' . Str::studly($way) . 'Way';

        if(class_exists($class)){
            return new $class($way);
        }

        throw new PayPaymentException('不支持的支付通道:' . $way);
    }
}

