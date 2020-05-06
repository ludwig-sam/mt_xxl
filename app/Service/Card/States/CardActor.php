<?php namespace App\Service\Card\States;

use App\Exceptions\CardException;
use App\Http\Codes\Code;
use Libs\Log;
use Libs\Time;
use App\Models\CardCodeModel;
use App\DataTypes\CardCodeStatus;
use App\Models\CardModel;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use App\Models\MchCardsModel;
use App\Service\Card\Contracts\CardCanable;
use App\Service\Member\Member;
use App\Service\Service;
use App\Service\Users\MemberUser;
use App\Service\Wechat\Card;
use Illuminate\Support\Collection;


class CardActor extends Service implements CardCanable {

    private $state;
    private $cardInfo;

    private $stateDisabled;
    private $stateSending;
    private $wxCardId;
    private $cardId;
    private $stateNotExists;

    public function __construct($wxCardId, $cardId = 0)
    {

        $this->wxCardId      = $wxCardId;
        $this->cardId        = $cardId;
        $this->cardInfo      = $this->getCardInfo();
        $this->stateDisabled = new Disabled($this);
        $this->stateSending  = new Sending($this);
        $this->stateNotExists  = new NotExists($this);

        if(!$this->cardInfo){
            $this->state = $this->stateNotExists;
        }else{
            switch ($this->cardInfo['status']){
                case CardStatus::disabled:
                    $this->state = $this->stateDisabled;
                    break;
                default:
                    $this->state = $this->stateSending;
            }
        }

    }

    public function getCardInfo(){
        if($this->cardInfo)return $this->cardInfo;

        $where = $this->cardId ? ['id' => $this->cardId] : ['card_id' => $this->wxCardId];

        $this->cardInfo = (new CardModel($where))->where($where)->first();

        if(!$this->cardInfo){
            $this->setError("卡券不存在", Code::card_not_exists);
            return false;
        }

        return $this->cardInfo;
    }

    public function activate($code)
    {
        return $this->state->activate($code);
    }

    public function receive($code){
        return $this->state->receive($code);
    }

    public function canUse()
    {
        return $this->state->canUse();
    }

    public function cardId()
    {
        return $this->getCardInfo()['id'];
    }

    public function checkMch($mch_id)
    {
        $mch_cards_model = new MchCardsModel();

        $card_info       = $this->getCardInfo();

        $support_mch_id  = $card_info['mch_id'];

        if($support_mch_id){
            $mchs = $mch_cards_model->getMchs($this->cardId());

            if(!in_array($mch_id, $mchs)){
                throw new CardException("此卡券只能在特定商户下面使用:{$mch_id}");
            }
        }

    }

    public function delete($id)
    {
        return $this->state->delete($id);
    }

    public function user(){
        return MemberUser::getInstance();
    }

    public function grant($outStr)
    {
        return $this->state->grant($outStr);
    }

    function consume($code, $out_str = null)
    {
        return $this->state->consume($code, $out_str);
    }

    function doConsume($code, $out_str)
    {
        $wechat_card = new Card();
        $code_model  = new CardCodeModel();

        $code_row = $code_model->where('card_id', $this->cardId)->where('code_no', $code)->first();

        if(!$code_row){
            throw new CardException('无效的 code:' . $code, Code::invalid_param, compact('code'));
        }

        if(!$wechat_card->consume($code, null, $out_str)){
            throw new CardException($wechat_card->result()->getMsg(), Code::fail, compact('code'));
        }

        return true;
    }

    private function notEnough()
    {
        if($this->getCardInfo()['quantity'] <= 0){
            throw new CardException("库存不足");
        }
    }

    private function maxGetLimit()
    {
        $card_id = $this->getCardInfo()['id'];
        $get_limit = $this->getCardInfo()['get_limit'];
        if($this->user()->getId() > 0 && $get_limit > 0){
            $card_code_model = new CardCodeModel();

            $receive_count = $card_code_model->where('card_id', $card_id)->where('member_id', $this->user()->getId())->count();

            if($receive_count >= $get_limit){
                throw new CardException("此卡每人限领：" . $get_limit . '张');
            }
        }
    }

    public function doGrant($outStr)
    {
        $this->notEnough();
        $this->maxGetLimit();

        $wechatCardService = new Card();
        $wxCardId          = $this->getCardInfo()['card_id'];
        return $wechatCardService->cardExt($wxCardId, $outStr);
    }

    public function doReceive($code)
    {
        $cardInfo       = $this->getCardInfo();
        $cardCodeModel  = new CardCodeModel();

        $exists = $cardCodeModel->where('code_no', $code)->where('member_id', $this->user()->getId())->first();

        if($exists)return true;

        $cardId                     = $cardInfo['id'];
        $cardCodeModel->code_no     = $code;
        $cardCodeModel->card_id     = $cardId;
        $cardCodeModel->member_id   = $this->user()->getId();
        $cardCodeModel->status      = CardCodeStatus::receive;

        $date = json_decode($cardInfo['date_info'], true);

        if($date){
            $date = new Collection($date);

            switch ($date->get('type')){
                case CardTypes::date_type_range:
                        $cardCodeModel->start_time = $date->get('begin_timestamp', \time());
                        $cardCodeModel->end_time   = $date->get('end_timestamp', 0);
                    break;
                case CardTypes::date_type_fix_term:

                    $startTime = $date->get('fixed_begin_term', 0) == 0 ? time() : Time::dateAfter($date->get('fixed_begin_term'));
                    $endTime   = Time::dateAfter($date->get('fixed_term'));

                    if($date->get('end_timestamp')){
                        $endTime = $date['end_timestamp'];
                    }

                    $cardCodeModel->start_time = $startTime;
                    $cardCodeModel->end_time   = $endTime;
                    break;
            }
        }

        $cardCodeModel->save();

        $cardModel = new CardModel();

        $cardCodeModel = $cardModel->find($cardId);
        $cardCodeModel->quantity--;
        $cardCodeModel->receive_quantity++;

        $cardCodeModel->save();

        return true;
    }

}