<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/15
 * Time: 下午12:40
 */

namespace App\Service\Wechat\Reply\Contraicts;

use Abstracts\ReplyHanlderInterface;
use Abstracts\ReplyMessageInterface;


class ReplyAbstracts implements ReplyHanlderInterface
{
    protected $msgObj;

    public function __construct(ReplyMessageInterface $msgObj)
    {
        $this->msgObj = $msgObj;
    }

    public function eventKey()
    {
        $event_key = $this->msgObj->getAttr('EventKey');

        if($event_key !== null){
            return $event_key;
        }

        return $this->eventName();
    }

    public function eventName()
    {
        return $this->msgObj->getAttr('MsgType') . '_' . $this->msgObj->getAttr('Event');
    }
}