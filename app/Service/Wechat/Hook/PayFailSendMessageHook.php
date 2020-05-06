<?php namespace App\Service\Wechat\Hook;



use Abstracts\ListenerInterface;
use Abstracts\ReplyMessageInterface;
use App\Exceptions\MessageSendException;
use App\Models\MemberModel;
use App\DataTypes\MessageSendRoots;
use App\Models\PayOrderDetailModel;
use App\Models\PayOrderModel;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\Hook\Traits\OrderChangeNotifyTrait;
use App\Service\Listener\PayNotifyListener;
use Providers\ReplyReceiveMessage;


class PayFailSendMessageHook  implements HookInterface {


    use OrderChangeNotifyTrait;

    /**
     * @var ReplyMessageInterface
     */
    private $message;

    public function name()
    {
        return 'pay_fail_notify_execute';
    }

    public function memberId()
    {
        return $this->message->getAttr('member_id');
    }

    public function getOpenid()
    {
        $member_model = new MemberModel();

        $row = $member_model->find($this->memberId());

        if(!$row){
            throw new MessageSendException("会员不存在:" . $this->memberId());
        }

        return $row->openid;
    }

    private function specialGetUsers()
    {
        return [$this->getOpenid()];
    }

    function templateName()
    {
        return MessageSendRoots::pay_fail_notify;
    }

    public function hanlder(ReplyMessageInterface $msgObj)
    {
        $this->message = $msgObj;

        return $this->do($msgObj);
    }

    private function orderId()
    {
        return $this->message->getAttr('id');
    }

    private function messageFillDetial()
    {
        $order_model  = new PayOrderDetailModel();

        $order_detial = $order_model->getByOrderId($this->orderId());


        if($order_detial) {
            $all = $this->message->toArray();

            $all = array_merge($all, $order_detial->toArray());

            $this->message = new ReplyReceiveMessage($all);

        }
    }

    public function do(ReplyMessageInterface $message)
    {
        $this->messageFillDetial();

        if(!$this->memberId()){
            return true;
        }

        $row = $this->getTemplate();

        if(!$row){
            throw new MessageSendException("没有找到支付失败通知模板消息的配置");
        }

        $this->send($row);

        return true;
    }

    function listener(): ListenerInterface
    {
        return new PayNotifyListener();
    }

}