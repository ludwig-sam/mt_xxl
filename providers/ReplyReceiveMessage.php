<?php namespace Providers;


use Abstracts\ReplyMessageInterface;
use Providers\Reply\ReplyMessageArray;
use Providers\Reply\ReplyMessageObject;
use Providers\Reply\ReplyMessageXml;

class ReplyReceiveMessage extends ReplyMessageInterface {
    private $container;

    public function __construct($message)
    {
        switch (gettype($message)){
            case 'string':
                    $this->container = new ReplyMessageXml($message);
                break;
            case 'array':
                    $this->container = new ReplyMessageArray($message);
                break;
            case 'object':
                    $this->container = new ReplyMessageObject($message);
                break;
            default:
                    $this->container = new ReplyMessageArray([]);
                break;
        }
    }

    public function getAttr($attr)
    {
        return $this->container->getAttr($attr);
    }

    public function setAttr($attr, $value)
    {
        $this->container->setAttr($attr, $value);
    }

    public function toArray()
    {
        return $this->container->toArray();
    }

    public function merge(ReplyMessageInterface $replyReceiveMessage){
        $attrs = $replyReceiveMessage->toArray();
        foreach ($attrs as $name => $attr){
            $this->container->setAttr($name, $attr);
        }
    }

    public function __get($name)
    {
        return $this->container->getAttr($name);
    }
}