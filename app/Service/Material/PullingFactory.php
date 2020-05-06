<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午12:12
 */

namespace App\Service\Material;



use App\Exceptions\MaterialException;
use App\Service\Material\Contracts\MaterialAbsctracts;
use App\Service\Material\Contracts\PullingInterface;
use Illuminate\Support\Str;

class PullingFactory
{
    public static function make($type, $is_mook = false):PullingInterface
    {
        $studlyType = Str::studly($type);
        $class      = __NAMESPACE__ . '\\Pulling\\' . $studlyType . 'Pull';

        if(class_exists($class)){
            return new $class($is_mook);
        }

        throw new MaterialException("不支持的素材类型接口:" . $studlyType);
    }
}