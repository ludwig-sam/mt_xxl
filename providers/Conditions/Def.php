<?php namespace Providers\Conditions;

use Providers\Conditions\Contracts\ConditionContract;
use Providers\Hook\Contracts\HookMessageContract;

class Def implements ConditionContract
{

    public function matched(HookMessageContract $msgObj, $conditionKey, $conditionVal)
    {
        return true;
    }

    public function isMe($conditonOp)
    {
        return $conditonOp === null;
    }

}