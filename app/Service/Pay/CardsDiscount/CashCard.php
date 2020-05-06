<?php

namespace App\Service\Pay\CardsDiscount;

use Abstracts\Offsetable;
use App\Exceptions\PayPaymentException;


class CashCard extends \App\Service\Pay\Contracts\Discounter {



    public function canDis($totalAmount)
    {

        $leastCost = array_get(self::getCard(), 'least_cost');

        if($totalAmount < $leastCost){
            throw new PayPaymentException("不能使用该代金券：订单金额必须大于等于" . $leastCost);
        }

        return true;
    }

    public function discount($totalAmount, Offsetable $offsetable)
    {
        return max(0, $totalAmount - array_get(self::getCard(), 'reduce_cost'));
    }

}