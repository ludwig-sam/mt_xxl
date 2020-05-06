<?php namespace App\Service\Wechat\Hook;



use Abstracts\ReplyMessageInterface;
use Libs\Log;
use Libs\Str;
use App\Models\CardCodeModel;
use App\Models\CardModel;
use App\Models\MchModel;
use App\Models\StoreModel;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\Hook\Traits\ConsumeLogTrait;

class ExeConsumeCardLogHook  implements HookInterface {

    use ConsumeLogTrait;

    /**
     * @var ReplyMessageInterface
     */
    private $message;


    public function hanlder(ReplyMessageInterface $message)
    {
        $this->message = $message;

        $this->saveLog($this->getCardId(), $this->getCardCode(), $this->getOutStr());

        $this->consumeStoreLog($this->getCardId(), $this->getCardCode());
    }

    private function getCardCode()
    {
        return trim($this->message->getAttr('UserCardCode'));
    }

    private function getWxCardId()
    {
        return $this->message->getAttr('CardId');
    }

    private function getCardId()
    {
        $card_model = new CardModel();
        $card_id    = $card_model->getCardIdByWxCardId($this->getWxCardId());

        return $card_id;
    }

    private function getOriginOutStr()
    {
        return $this->message->getAttr('OuterStr');
    }

    private function getOutStr()
    {
        return Str::first($this->getOriginOutStr(), ':');
    }

    private function getExetendsInfo()
    {
        return explode('_', Str::last($this->getOriginOutStr(), ':'));
    }

    function storeId()
    {
        return $this->getExeField('store_id');
    }

    function opratorId()
    {
        return $this->getExetendsInfo()[0];
    }

    function exeId()
    {
        return $this->getExetendsInfo()[1];
    }

    function memberId()
    {
        $card_code_model = new CardCodeModel();

        return $card_code_model->getMemberId($this->getCardCode(), $this->getCardId());
    }

    function orderNo()
    {
        return '';
    }

    function mchId()
    {
        $store_model = new StoreModel();

        return $store_model->where('id', $this->storeId())->value('mch_id');
    }

    function consumeStoreLog($card_id, $code)
    {
        if(!$this->storeId())return ;

        $code_model = new CardCodeModel();

        $code_model->where('card_id', $card_id)->where('code_no', $code)->first()->update([
            'consume_mch_id'   => $this->mchId(),
            'consume_store_id' => $this->storeId()
        ]);
    }


}