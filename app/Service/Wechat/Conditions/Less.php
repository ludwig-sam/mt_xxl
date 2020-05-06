<?php namespace App\Service\Wechat\Conditions;


use Abstracts\ReplyMatcherInterface;
use Abstracts\ReplyMessageInterface;

class Less implements ReplyMatcherInterface {

    public function matched(ReplyMessageInterface $msgObj, $conditionKey, $conditionVal)
    {
         $xmlAttrValue = $msgObj->getAttr($conditionKey);

         if($xmlAttrValue === null){
             return false;
         }

         return $xmlAttrValue < $conditionVal;
    }

    public function isMe($conditonOp)
    {
        return strtolower($conditonOp) == 'less_than';
    }

}