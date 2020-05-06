<?php namespace Providers\Conditions;


use Providers\Conditions\Contracts\ConditionContract;
use Providers\Hook\Contracts\HookMessageContract;

class Less implements ConditionContract
{

    public function matched(HookMessageContract $msgObj, $conditionKey, $conditionVal)
    {
        $xmlAttrValue = $msgObj->get($conditionKey);

        if ($xmlAttrValue === null) {
            return false;
        }

        return $xmlAttrValue < $conditionVal;
    }

    public function isMe($conditonOp)
    {
        return strtolower($conditonOp) == 'less_than';
    }

}