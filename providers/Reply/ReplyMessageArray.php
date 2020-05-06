<?php namespace Providers\Reply;


use Abstracts\ReplyMessageInterface;

class ReplyMessageArray extends ReplyMessageInterface {
    private $container;

    public function __construct($container)
    {
        $this->pushContainer($container);
    }

    private function pushContainer($container)
    {
        $this->container = $container;
    }

    public function getAttr($attr)
    {
        return isset($this->container[$attr]) ? $this->container[$attr] : null;
    }

    public function setAttr($attr, $value)
    {
        $this->container[$attr] = $value;
    }

    public function toArray()
    {
        return $this->container;
    }
}