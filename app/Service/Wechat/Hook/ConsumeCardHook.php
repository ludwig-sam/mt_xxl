<?php namespace App\Service\Wechat\Hook;


use Libs\Log;
use Libs\Time;
use App\Models\CardCodeModel;
use App\DataTypes\CardCodeStatus;
use App\Exceptions\CardException;
use App\Models\CardModel;
use Abstracts\ReplyMessageInterface;
use App\DataTypes\MessageSendRoots;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Wechat\Hook\Contracts\HookInterface;

class ConsumeCardHook implements HookInterface, MessageProviderInterface
{

    /**
     * @var ReplyMessageInterface
     */
    private $message;

    public function getMessageTo()
    {
        return [$this->openid()];
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::consume_notify;
    }

    public function getMessageParam()
    {
        return $this->cardRow()->toArray();
    }

    private function wxCardId()
    {
        return $this->message->getAttr('CardId');
    }

    private function cardRow()
    {
        $card_model = new CardModel();

        return $card_model->getByWxCardId($this->wxCardId());
    }

    private function openid()
    {
        return $this->message->getAttr('FromUserName');
    }

    public function hanlder(ReplyMessageInterface $message)
    {

        $this->message = $message;

        MessageTirgger::instance()->trigger($this);

        $wxCardId = $message->getAttr('CardId');
        $cardCode = $message->getAttr('UserCardCode');

        try{
            $cardId = $this->getId($wxCardId);
            $this->save($cardId, $cardCode);

        }catch(\Exception  $exception){
            Log::error("卡券核销记录失败", ['wx_card_id' => $wxCardId, 'card_code' => $cardCode]);
        }


    }

    private function save($cardId, $cardCode)
    {
        $cardCodeModel = new CardCodeModel();
        $codeDb = $cardCodeModel->where('code_no', $cardCode)->where('card_id', $cardId)->first();

        if($codeDb){
            $codeDb->consume_at = Time::date();
            $codeDb->status = CardCodeStatus::consume;
        }else{
            $codeDb = $cardCodeModel->fill(['consume_at' => Time::date(), 'status' => CardCodeStatus::consume, 'code_no' => $cardCode, 'card_id' => $cardId]);
        }

        $codeDb->save();
    }

    private function getId($wxCardId)
    {
        $cardModel = new CardModel();
        $cardDb = $cardModel->where('card_id', $wxCardId)->first();

        if(!$cardDb){
            throw new CardException("卡券不存在:" . $wxCardId);
        }

        $cardDb->consume_quantity++;
        $cardDb->save();

        return $cardDb->id;
    }
}