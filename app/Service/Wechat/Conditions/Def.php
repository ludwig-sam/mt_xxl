<?php namespace App\Service\Wechat\Conditions;


use Abstracts\ReplyMatcherInterface;
use Abstracts\ReplyMessageInterface;

class Def implements ReplyMatcherInterface {

    public function matched(ReplyMessageInterface $msgObj, $conditionKey, $conditionVal)
    {
         return true;
    }

    public function isMe($conditonOp)
    {
        return $conditonOp === null;
    }

}