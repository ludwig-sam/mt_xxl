<?php

namespace App\Service\Pay\CardsDiscount;

use Abstracts\Offsetable;
use App\Exceptions\MemberException;
use App\Service\Member\Member;
use App\Service\MemberLevel\MchMemberLevel;


class MemberCardCard extends \App\Service\Pay\Contracts\Discounter
{


    public function canDis($totalAmount)
    {
        return true;
    }

    public function discount($totalAmount, Offsetable $offsetable)
    {
        $code  = $offsetable->offsetGet('card_code');
        $mchId = $offsetable->offsetGet('mch_id');

        $memberService = new Member();
        $memberInfo    = $memberService->getMemberByCode($code);

        if (!$memberInfo) {
            throw new MemberException("无效的会员码:" . $code, MemberException::invalid_member_code);
        }

        $mchLvService = new MchMemberLevel($mchId, $memberInfo['level']);

        return $mchLvService->discount($totalAmount);
    }

}