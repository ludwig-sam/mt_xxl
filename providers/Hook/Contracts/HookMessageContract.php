<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/9
 * Time: 下午3:30
 */

namespace Providers\Hook\Contracts;


abstract class HookMessageContract
{

    abstract function get($attr, $def = null);

    abstract function set($attr, $value);

    abstract function toArray();

    public function merge(HookMessageContract $message)
    {
        $attrs = $message->toArray();

        foreach ($attrs as $name => $attr) {

            $this->set($name, $attr);
        }
    }

    function __get($name)
    {
        return $this->get($name);
    }

}