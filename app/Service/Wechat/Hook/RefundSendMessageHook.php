<?php namespace App\Service\Wechat\Hook;


use Abstracts\ListenerInterface;
use Abstracts\ReplyMessageInterface;
use App\Exceptions\PayPaymentException;
use App\Http\Codes\PayCode;
use App\Models\MemberModel;
use App\DataTypes\MessageSendRoots;
use App\Service\Listener\RefundNotifyListener;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\Hook\Traits\OrderChangeNotifyTrait;


class RefundSendMessageHook implements HookInterface, MessageProviderInterface
{

    use OrderChangeNotifyTrait;

    /**
     * @var ReplyMessageInterface
     */
    protected $message;

    public function getMessageParam()
    {
        $this->message->setAttr('reason', '收银员操作');
        return $this->message->toArray();
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::refund_success_notify;
    }

    public function memberId()
    {
        return $this->message->member_id;
    }

    private function member()
    {
        $model = new MemberModel();

        $row = $model->find($this->memberId());

        return $row;
    }

    public function getMessageTo()
    {

        $row = $this->member();

        if(!$row || !$row->openid){
            return [];
        }

        return [$row->openid];
    }

    function templateName()
    {
        return MessageSendRoots::refund_notify;
    }

    public function hanlder(ReplyMessageInterface $message)
    {

        $this->message = $message;

        MessageTirgger::instance()->trigger($this);

        $row = $this->getTemplate();

        if(!$row){
            $this->throw("没有找到退款通知的发送消息的配置");
        }

        $this->send($row);
    }

    function throw($msg)
    {
        throw new PayPaymentException($msg, PayCode::refund_fail, $this->message->toArray());
    }

    function listener():ListenerInterface
    {
        return new RefundNotifyListener();
    }

}