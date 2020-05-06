<?php

namespace App\Service\Pay\CardsDiscount;

use Abstracts\Offsetable;
use Libs\Unit;

class DiscountCard extends \App\Service\Pay\Contracts\Discounter {


    public function canDis($totalAmount)
    {
        return true;
    }

    public function discount($totalAmount, Offsetable $offsetable)
    {
        return max(0, Unit::fentoYun(($totalAmount * intval(array_get(self::getCard(), 'discount')) / 100) * 100));
    }

}