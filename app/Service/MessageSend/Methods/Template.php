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
use App\Service\MessageSend\Methods\Template\Types\Subscribe;
use App\Service\Wechat\Wechat;

class Template extends Wechat implements SendAble
{

    public function send(MessageInterface $message)
    {
        $sender = $this->serve()->template_message;

        $subscribeMessage = new Subscribe();

        if($message->getType() == $subscribeMessage->getType()){
            $this->parseResult($sender->sendSubscription($message->getContent()));
        }else{
            $this->parseResult($sender->send($message->getContent()));
        }

        return $this->result()->isSuccess();
    }

}