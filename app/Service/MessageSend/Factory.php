<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午10:22
 */

namespace App\Service\MessageSend;


use App\Exceptions\MessageSendException;
use App\Http\Codes\Code;
use App\Service\MessageSend\Contracts\MessageInterface;
use App\Service\MessageSend\Contracts\SendAble;
use Illuminate\Support\Str;

class Factory
{

    private static $methodClass;
    private static $method;

    public static function make($method) : SendAble
    {
        self::$method = $method;

        $methodClass = __NAMESPACE__ . "\\Methods\\" . Str::studly($method);

        if(!class_exists($methodClass)){
            throw new MessageSendException('不存在的发送方式：' . $method, Code::message_method_not_exists);
        }

        self::$methodClass = $methodClass;

        return new $methodClass();
    }

    public static function message($filter, $type) : MessageInterface
    {
        $typeClass   = self::type(self::$methodClass, $type);
        $message     = new $typeClass;
        if(!$filter){
            return $message;
        }
        $filterClass = self::filter(self::$methodClass, $filter);
        $filterObj   = new $filterClass($message);
        return $filterObj;
    }

    private static function filter($methodClass, $name)
    {
        $messageClass = $methodClass . "\\Filters\\" . Str::studly($name) . "Filter";

        if(!class_exists($messageClass)){
            throw new MessageSendException('不支持的过滤方式：' . self::$method . '.' . $name, Code::message_method_not_exists);
        }

        return $messageClass;
    }

    private static function type($methodClass, $type)
    {
        $messageClass = $methodClass . "\\Types\\" . Str::studly($type);

        if(!class_exists($messageClass)){
            throw new MessageSendException('不支持的消息类型：' . self::$method . '.' .  $type, Code::message_method_not_exists);
        }

        return $messageClass;
    }


}