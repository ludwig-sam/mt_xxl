<?php namespace App\Service\Card\States;

use App\Http\Codes\Code;
use App\Models\CardCodeModel;
use App\DataTypes\CardCodeStatus;
use App\Models\CardModel;
use App\Service\Card\Contracts\CardCanable;
use App\Service\Wechat\Card;

class Sending implements CardCanable {

    private $cardActor;
    private $code;

    public function __construct(CardActor &$cardAction)
    {
        $this->cardActor = $cardAction;
    }

    function activate($code)
    {
        $cardService = new Card();
        $this->code  = $code;
        $cardInfo    = $this->cardActor->getCardInfo();

        if(!$this->doActiate($cardService, $cardInfo['card_id']))return false;

        return $this->update($cardInfo['id']);
    }

    private function update($cardId)
    {
        $cardCodeModel = new CardCodeModel();
        $cardCodeModel->code_no     = $this->code;
        $cardCodeModel->card_id     = $cardId;

        $oldCode = $cardCodeModel->where(['card_id' => $cardId, 'code_no' => $this->code])->first();

        if($oldCode){
            $oldCode->status      = CardCodeStatus::activated;
            $ret = $oldCode->save();
        }else{
            $cardCodeModel->member_id   = $this->cardActor->user()->getId();
            $cardCodeModel->status      = CardCodeStatus::activated;
            $ret = $cardCodeModel->save();
        }

        if(!$ret){
            $this->cardActor->setError("保存失败");
            return false;
        }

        $this->cardActor->setAttribute('codeInfo', $cardCodeModel->toArray());

        return $cardCodeModel->id;
    }

    private function doActiate(Card $card, $cardId)
    {
        $activateInfo = [
            'membership_number' => $this->code,
            'code'              => $this->code,
            'card_id'           => $cardId,
        ];
        if(!$card->memberCardActivate($activateInfo)){
            $this->cardActor->setError($card->result()->getMsg(), Code::wx_activate_fail);
            return false;
        }

        return true;
    }

    function delete($id)
    {
        $this->cardActor->setError( "卡券发放中，不能删除");
        return false;
    }

    function receive($code)
    {
        $this->cardActor->doReceive($code);
        return true;
    }

    public function grant($outStr)
    {
        return $this->cardActor->doGrant($outStr);
    }

    function canUse()
    {
        return true;
    }

    public function consume($code, $out_str = null)
    {
        return $this->cardActor->doConsume($code, $out_str);
    }
}