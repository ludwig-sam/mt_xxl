<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/9
 * Time: 下午4:00
 */

namespace Providers\Hook;


use Providers\Conditions\Contracts\ConditionContract;
use Providers\Conditions\Def;
use Providers\Conditions\Equal;
use Providers\Conditions\Greater;
use Providers\Conditions\Less;
use Providers\Conditions\Preg;
use Providers\Hook\Contracts\HookMessageContract;

class Matcher
{
    private function conditons()
    {
        $conditions = [
            new Equal(),
            new Greater(),
            new Less(),
            new Preg(),
        ];

        //默认条件必须在最后一个匹配
        $conditions[] = new Def();

        return $conditions;
    }

    public function configOption($config)
    {
        $option = new ConfigOption();

        $option->name     = $config['name'];
        $option->is_async = $config['is_async'];
        $option->delay    = $config['delay'];

        return $option;
    }

    public function isMatched(Config $hooks, HookMessageContract $message, Hook $executor)
    {
        $hooks = $hooks->config();

        foreach ($hooks as $config) {
            if ($this->loopMatch($config, $message)) {

                $executor->callback($this->configOption($config), $message);
            }
        }

        return false;
    }

    private function loopMatch($config, HookMessageContract $message)
    {
        foreach ($this->conditons() as $conditon) {
            if ($this->matched($conditon, $message, $config)) {
                return true;
            }
        }

        return false;
    }

    private function matched(ConditionContract $condition, HookMessageContract $messageContract, $config)
    {
        $op  = $config['condition_op'];
        $key = $config['condition_key'];
        $val = $config['condition_val'];

        return ($condition->isMe($op) && $condition->matched($messageContract, $key, $val));
    }
}