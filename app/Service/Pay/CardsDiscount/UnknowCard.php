<?php

namespace App\Service\Pay\CardsDiscount;

use Abstracts\Offsetable;
use App\Exceptions\PayPaymentException;

class UnknowCard extends \App\Service\Pay\Contracts\Discounter {


    public function canDis($totalAmount)
    {
        throw new PayPaymentException("此卡券不支持优惠:" . self::getType());
    }

    public function discount($totalAmount, Offsetable $offsetable)
    {
        return $totalAmount;
    }

}