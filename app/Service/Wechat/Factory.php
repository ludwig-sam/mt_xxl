<?php namespace App\Service\Wechat;



use Abstracts\MessageTransformInterface;
use Abstracts\ReplyMessageInterface;
use App\Service\Wechat\Message\DefaultMessage;
use App\Service\Wechat\Reply\DefaultReply;

class Factory{

    private static function getClassName($msgType, $belong){
        return ('\\App\\Service\\Wechat\\'. ucfirst($belong) .'\\'. ucfirst($msgType) . ucfirst($belong));
    }

    public static function reply($msgType, ReplyMessageInterface $msgObj){
        $class   = self::getClassName($msgType, 'reply');
        if(class_exists($class)){
            return  new $class($msgObj);
        }
        return new DefaultReply($msgObj);
    }

    public static function message($msgType, ReplyMessageInterface $msgObj) : MessageTransformInterface
    {
        if($msgType){
            $class   = self::getClassName($msgType, 'message');
            if(class_exists($class))return  new $class($msgObj);
        }
        return new DefaultMessage($msgObj);
    }

}