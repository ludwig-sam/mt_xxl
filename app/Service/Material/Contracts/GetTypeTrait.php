<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午12:13
 */

namespace App\Service\Material\Contracts;


use Libs\Str;

trait GetTypeTrait
{
    private static $type;
    public function getType($ext   = 'Material')
    {
        if(self::$type)return self::$type;

        $class = get_class($this);
        self::$type = \Illuminate\Support\Str::snake(substr(Str::last($class, '\\'), 0, -strlen($ext)));

        return self::$type;
    }
}