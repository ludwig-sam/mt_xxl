<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/19
 * Time: 下午4:07
 */

namespace Providers\Single;


trait SingleAble
{
    protected function single(\Closure $fun, $name = 'common')
    {
        static $instances;

        if (!$instances || !isset($instances[$name])) {
            $instances[$name] = $fun();
        }

        return $instances[$name];
    }

    protected function newSingle($class)
    {
        return $this->single(function () use ($class) {
            return new $class;
        }, $class);
    }
}