<?php namespace App\Service\Pay;


use Abstracts\Offsetable;
use App\Service\Card\States\CardActor;
use App\Service\Pay\Contracts\Discounter;
use App\Service\Service;
use App\Service\Users\CachierUser;

class Discount extends  Service{

    private $cardCodes = [];

    public function calculationAmount($totalAmount, Offsetable $offsetable)
    {
        $amount = $totalAmount;
        foreach ($this->cardCodes as  $cardCode){
            $cardId   = $cardCode[0];

            $cardActor = new CardActor(null, $cardId);

            $cardActor->canUse();

            $cardInfo  = $cardActor->getCardInfo();

            $this->canUse($cardActor);

            $discounter = Discounter::make($cardInfo);

            $discounter->canDis($totalAmount);

            $amount = $discounter->discountPrcie($amount, $offsetable);

            if(!$this->canOverlay($cardInfo))break;
        }

        return $amount;
    }

    private function canUse(CardActor $cardActor)
    {
        $cardActor->checkMch(CachierUser::getInstance()->getMchId());
    }

    private function canOverlay($cardInfo)
    {
        return $cardInfo['can_overlay'] == 1;
    }

    public function pushCards($cardId, $code)
    {
        if($cardId && $code){
            $this->cardCodes[] = [
                $cardId,
                $code
            ];
        }
    }

}