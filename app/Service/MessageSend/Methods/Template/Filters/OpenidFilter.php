<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:47
 */

namespace App\Service\MessageSend\Methods\Template\Filters;



use App\Service\MessageSend\Contracts\MessageInterface;

class OpenidFilter implements MessageInterface
{

    private $messageInstance;
    private $content;
    private $to;

    public function __construct(MessageInterface $messageInstance)
    {
        $this->messageInstance = $messageInstance;
    }

    public function getContent()
    {
        $this->content['touser'] = is_array($this->to) ? $this->to[0] : $this->to;
        return $this->content;
    }

    public function getType()
    {
        return $this->messageInstance->getType();
    }

    public function setMessage($to, $message)
    {
        $this->to      = is_array($to) ? $to[0] : $to;
        $this->content = $message;
    }

}