<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午12:15
 */

namespace Tests\Dispatch\Providers;


use Libs\Log;
use App\Service\Material\Contracts\GetTypeTrait;

abstract class HookAbsctracts implements  HookInterface
{
    use GetTypeTrait;

    function hanlder($msg)
    {
        Log::warning("正在执行测试:" . $this->getType('hook'));
        $this->do($msg);
    }

    abstract function do($msg);
}