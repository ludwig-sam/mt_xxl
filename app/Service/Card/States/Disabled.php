<?php namespace App\Service\Card\States;


use App\DataTypes\CardTypes;
use App\Exceptions\CardException;
use App\Http\Codes\Code;
use App\Service\Card\Contracts\CardCanable;

class Disabled implements CardCanable
{
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
        $this->cardActor->setError("卡券被禁用，不能激活", Code::card_disabled);

        return false;
    }

    public function receive($code)
    {
        $this->cardActor->doReceive($code);
        return true;
    }

    public function canUse()
    {
        $card_info = $this->cardActor->getCardInfo();

        if ($card_info['type'] == CardTypes::member_card) {
            $type_name = '会员卡';
        }

        throw new CardException($type_name . "卡券被禁用，不能使用", CardException::disabled);
    }

    public function grant($outStr)
    {
        throw new CardException("卡券被禁用", CardException::disabled);
    }

    public function consume($code, $out_str = null)
    {
        throw new CardException("卡券被禁用", CardException::disabled);
    }
}