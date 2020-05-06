<?php namespace Providers\Conditions;


use Providers\Conditions\Contracts\ConditionContract;
use Providers\Hook\Contracts\HookMessageContract;

class Preg implements ConditionContract
{

    public function matched(HookMessageContract $msgObj, $conditionKey, $conditionVal)
    {
        $xmlAttrValue = $msgObj->get($conditionKey);

        if ($xmlAttrValue === null) {
            return false;
        }

        return preg_match($conditionVal, $xmlAttrValue);
    }

    public function isMe($conditonOp)
    {
        return strtolower($conditonOp) == 'reg_exp';
    }

}