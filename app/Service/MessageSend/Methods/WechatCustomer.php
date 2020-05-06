<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:08
 */

namespace App\Service\MessageSend\Methods;

use App\Service\MessageSend\Contracts\MessageInterface;
use App\Service\MessageSend\Contracts\SendAble;
use App\Service\Wechat\Wechat;

class WechatCustomer extends Wechat implements SendAble
{

    public function send(MessageInterface $message)
    {
        $sender = $this->serve()->customer_service;

        $this->parseResult($sender->send($message->getContent()));

        return $this->result()->isSuccess();
    }

}