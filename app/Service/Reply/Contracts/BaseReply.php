<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:45
 */

namespace App\Service\Reply\Contracts;



use App\Service\Wechat\Factory;
use Providers\ReplyReceiveMessage;

abstract class BaseReply implements ReplySetAble
{
    abstract function msgType();
    abstract function event();

    public function getEventKey()
    {
        $reply = $this->getReply();

        return $reply->eventKey();
    }

    public function getEventName()
    {
        $reply = $this->getReply();

        return $reply->eventName();
    }

    private function getReply()
    {
        $message = new ReplyReceiveMessage([
            'MsgType' => $this->msgType(),
            'Event'   => $this->event()
        ]);
        $reply      = Factory::reply($this->msgType(), $message);

        return $reply;
    }
}