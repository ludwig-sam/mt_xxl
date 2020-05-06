<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:18
 */

namespace App\Service\MessageSend\Contracts;


abstract  class MessageMethodAbsctricts implements MessageInterface
{
    protected $messageInstacne;
    protected $message;
    protected $to;

    public function __construct(MessageInterface $messageInstacne)
    {
        $this->messageInstacne = $messageInstacne;
    }

    public function setMessage($to, $message)
    {
        $this->messageInstacne->setMessage($to, $message);
        $this->message = $message;
        $this->to = $to;
    }

    public function getType()
    {
        return $this->messageInstacne->getType();
    }

    public function getContent()
    {
        $content =  [
            "msgtype" => $this->messageInstacne->getType(),
            $this->messageInstacne->getType() => $this->messageInstacne->getContent()
        ];
        return array_merge($content, $this->filter());
    }

    abstract function filter();

}