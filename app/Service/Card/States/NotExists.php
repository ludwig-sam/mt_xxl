<?php namespace App\Service\Card\States;


use App\Exceptions\CardException;
use App\Service\Card\Contracts\CardCanable;

class NotExists implements CardCanable {
    private $cardActor;

    public function __construct(CardActor &$cardAction)
    {
        $this->cardActor = $cardAction;
    }

    public function delete($id)
    {
        return true;
    }

    public function activate($enCode)
    {
        throw new CardException('卡券不存在', CardException::card_not_exists);
    }

    public function receive($code)
    {
        throw new CardException('卡券不存在', CardException::card_not_exists);
    }

    public function canUse()
    {
        throw new CardException('卡券不存在', CardException::card_not_exists);
    }

    public function grant($outStr)
    {
        throw new CardException('卡券不存在', CardException::card_not_exists);
    }

    public function consume($code, $out_str = null)
    {
        throw new CardException('卡券不存在', CardException::card_not_exists);
    }
}