<?php

namespace App\Service\Pay\Contracts;

use Abstracts\Offsetable;
use App\Exceptions\CardException;
use Illuminate\Support\Str;

abstract class Discounter implements Dicscountable {

    private static $card;

    public static function getCard(){
        return self::$card;
    }

    public static function getType(){
        return strtolower(array_get(self::$card, 'type'));
    }

    public static function make($card):Dicscountable
    {
        self::$card = $card;

        if(!self::$card){
            throw new CardException("卡券信息未初始化",CardException::card_not_exists);
        }

        $class =  '\\App\\Service\\Pay\\CardsDiscount\\' . Str::studly(self::getType()) . 'Card';

        if(class_exists($class)){
            return new $class();
        }

        return new \App\Service\Pay\CardsDiscount\UnknowCard();
    }

    public function discountPrcie($totalAmount, Offsetable $offsetable){
        return max((string)$this->discount($totalAmount, $offsetable), '0.01');
    }

    abstract protected function discount($totalAmount, Offsetable $offsetable);
}