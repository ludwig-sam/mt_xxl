<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午11:42
 */

namespace Providers\Event\MsgTranslator;


use Providers\Hook\Contracts\HookMessageContract;

class MsgTranslaterArray extends HookMessageContract
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function get($attr, $def = null)
    {
        return array_get($this->container, $attr, $def);
    }

    public function set($attr, $value)
    {
        array_set($this->container, $attr, $value);
    }

    public function toArray()
    {
        return $this->container;
    }

}