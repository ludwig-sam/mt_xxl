<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午11:42
 */

namespace Providers\Event\MsgTranslator;


use Providers\Hook\Contracts\HookMessageContract;

class MsgTranslaterObject extends HookMessageContract
{

    private $container;

    public function __construct($container)
    {
        $this->pushContainer($container);
    }

    private function pushContainer($container)
    {
        $this->container = $container;
    }

    public function get($attr, $def = null)
    {
        return isset($this->container->$attr) ? $this->container->$attr : $def;
    }

    public function set($attr, $value)
    {
        $this->container->$attr = $value;
    }

    public function toArray()
    {
        $result = [];
        foreach ($this->container as $k => $v) {
            $result[$k] = $v;
        }
        return $result;
    }

}