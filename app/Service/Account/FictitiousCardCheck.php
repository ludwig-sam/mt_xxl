<?php

namespace App\Service\Account;


use App\DataTypes\FictitiousCardTypes;
use App\Exceptions\CardException;
use App\Service\Row\FictitousCardRow;

class FictitiousCardCheck
{

    static public function checkCardCanUse(FictitousCardRow $card)
    {
        if ($card->isDisabled()) {
            throw new CardException('充值卡被禁用');
        }
    }

    static public function checkCodeCanUse($code)
    {
        if ($code->status == FictitiousCardTypes::code_status_used) {
            throw new CardException('充值卡已使用过');
        }
    }

    static public function checkTermOfValidity(FictitousCardRow $card)
    {
        self::checkHasStarted($card);

        self::checkOverdue($card);
    }

    static public function checkHasStarted(FictitousCardRow $couponRow)
    {
        if ($couponRow->isPermanent()) {
            return;
        }

        if ($couponRow->startTime() > time()) {
            throw new CardException($couponRow->startAt() . "后方可使用");
        }
    }

    static public function checkOverdue(FictitousCardRow $couponRow)
    {
        if ($couponRow->isPermanent()) {
            return;
        }

        if ($couponRow->endTime() <= time()) {
            throw new CardException("充值卡已过期");
        }
    }

}