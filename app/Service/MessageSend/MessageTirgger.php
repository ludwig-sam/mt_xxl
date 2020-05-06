<?php

namespace App\Service\MessageSend;


use Abstracts\ListenerInterface;
use Abstracts\ReplyMessageInterface;
use App\Service\Listener\MessageSendListener;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\Service;
use App\Service\Wechat\Hook\Traits\OrderChangeNotifyTrait;
use Providers\ReplyReceiveMessage;

class MessageTirgger extends Service
{

    use OrderChangeNotifyTrait;

    /**
     * @var ReplyMessageInterface
     */
    private $message;


    /**
     * @var MessageProviderInterface
     */
    private $mesage_privder;

    function templateName()
    {
        return $this->mesage_privder->getMessageTemplateName();
    }

    public function listener():ListenerInterface
    {
        return new MessageSendListener();
    }

    private function getUsers()
    {
        return $this->mesage_privder->getMessageTo();
    }

    public function trigger(MessageProviderInterface $message_provider)
    {
        $this->mesage_privder = $message_provider;

        $param = $message_provider->getMessageParam();

        $template = $this->getTemplate();

        $this->message = new ReplyReceiveMessage($param);

        $this->send($template);
    }

    public static function instance()
    {
        return (new self);
    }


}