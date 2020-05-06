<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午12:15
 */

namespace Tests\Dispatch\Providers;


interface HookInterface
{
    function hanlder($msg);
}