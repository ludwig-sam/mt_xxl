<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:04
 */

namespace App\Service\Reply;


use App\Service\Reply\Contracts\ReplySetAble;
use App\Service\Reply\Replys\GeneralReply;
use Illuminate\Support\Str;

class ReplyFactory
{
    static function make($name) : ReplySetAble
    {

        $studlyType = Str::studly($name);
        $class      = __NAMESPACE__ . '\\Replys\\' . $studlyType . 'Reply';

        if(class_exists($class)){
            return new $class($name);
        }

        return new GeneralReply($name);
    }
}