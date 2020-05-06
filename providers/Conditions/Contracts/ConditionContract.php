<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/9
 * Time: 下午4:15
 */

namespace Providers\Conditions\Contracts;


use Providers\Hook\Contracts\HookMessageContract;

interface ConditionContract
{
    function matched(HookMessageContract $msgObj, $conditionKey, $conditionVal);

    function isMe($conditonOp);
}
