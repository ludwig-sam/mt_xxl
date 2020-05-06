<?php namespace App\Service\Wechat\Conditions;


use Abstracts\ReplyMatcherInterface;
use Abstracts\ReplyMessageInterface;

class Preg implements ReplyMatcherInterface {

    public function matched(ReplyMessageInterface $msgObj, $conditionKey, $conditionVal)
    {
         $xmlAttrValue = $msgObj->getAttr($conditionKey);

         if($xmlAttrValue === null){
             return false;
         }

         return preg_match($conditionVal, $xmlAttrValue);
    }

    public function isMe($conditonOp)
    {
        return strtolower($conditonOp) == 'reg_exp';
    }

}