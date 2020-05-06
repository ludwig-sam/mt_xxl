<?php namespace App\Service\Wechat\Hook;



use Abstracts\ReplyMessageInterface;
use App\Models\CardModel;
use App\Models\ExeModel;
use App\Models\MemberModel;
use App\DataTypes\MessageSendLogTypes;
use App\DataTypes\MessageSendTypes;
use App\Service\MessageSend\Factory;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;


class PaySuccessSendCardHook  extends PaySuccessExtendsAbstracts {

    public function name()
    {
        return 'send_card_execute';
    }

    public function do(ReplyMessageInterface $message)
    {
        $exeId    = $message->getAttr('exe_id');
        $memberId = $message->getAttr('member_id');

        if(!$memberId)return ;

        if(!$exeId)$this->throw("未找到支付收银台ID");

        $exeModel = new ExeModel();
        $exeRow   = $exeModel->find($exeId);

        if(!$exeRow)$this->throw("没找到收银台");

        if(!$exeRow->card_id){
            $this->success();
            return ;
        }

        $cardModel = new CardModel();

        $cardRow   = $cardModel->find($exeRow->card_id);

        if(!$cardRow)$this->throw("无效的card_id");

        $memberModel = new MemberModel();

        $memberRow = $memberModel->find($memberId);

        if(!$memberRow)$this->throw('会员不存在');

        $sender     = Factory::make(MessageSendTypes::type_customer);
        $theMessage = Factory::message('openid', 'card');

        $theMessage->setMessage([$memberRow->openid], ['card_id' => $cardRow->card_id]);

        if(!$sender->send($theMessage)){
            $this->throw($sender->result()->getMsg());
        }

        $this->success();
    }
}