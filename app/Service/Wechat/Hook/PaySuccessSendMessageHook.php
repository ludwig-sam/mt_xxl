<?php namespace App\Service\Wechat\Hook;


use Abstracts\ListenerInterface;
use Abstracts\ReplyMessageInterface;
use App\Models\MemberModel;
use App\DataTypes\MessageSendRoots;
use App\Models\StoreModel;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Pay\Bill;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;
use App\Service\Wechat\Hook\Traits\OrderChangeNotifyTrait;
use App\Service\Listener\PayNotifyListener;


class PaySuccessSendMessageHook extends PaySuccessExtendsAbstracts implements MessageProviderInterface
{


    use OrderChangeNotifyTrait;

    private $message_param = [];

    public function name()
    {
        return 'pay_notify_execute';
    }

    private function memberId()
    {
        return $this->message->getAttr('member_id');
    }

    private function mchId()
    {
        return $this->message->getAttr('mch_id');
    }

    private function storeId()
    {
        return $this->message->getAttr('store_id');
    }

    public function getMessageTo()
    {
        $member     = new MemberModel();
        $member_row = $member->find($this->memberId());

        if (!$member_row || !$member_row->openid) {
            return [];
        }

        return [$member_row->openid];
    }

    public function getMessageParam()
    {
        return array_merge($this->message->toArray(), $this->message_param);
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::pay_success_notify;
    }

    function templateName()
    {
        return MessageSendRoots::pay_notify;
    }

    public function do(ReplyMessageInterface $message)
    {

        MessageTirgger::instance()->trigger($this);

        $row = $this->getTemplate();

        if (!$row) {
            $this->throw("没有找到收款通知的发送消息的配置");
        }

        $bill        = new Bill();
        $store_model = new StoreModel();

        $this->message->setAttr('store_name', $store_model->getName($this->storeId()));
        $this->message->setAttr('today_total', $bill->totalToday($this->mchId()));
        $this->message->setAttr('today_num', $bill->countNumToday($this->mchId()));

        $this->send($row);

        $this->success();
    }

    function listener():ListenerInterface
    {
        return new PayNotifyListener();
    }

}