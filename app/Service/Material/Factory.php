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
use Illuminate\Support\Str;

class Factory
{
    public static function make($type):MaterialAbsctracts
    {
        $studlyType = Str::studly($type);
        $class      = __NAMESPACE__ . '\\Materials\\' . $studlyType . 'Material';

        if(class_exists($class)){
            return new $class;
        }

        throw new MaterialException("不支持的素材类型接口:" . $studlyType);
    }
}