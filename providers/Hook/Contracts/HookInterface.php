<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午3:57
 */

namespace Providers\Hook\Contracts;



interface HookInterface
{
    function handle(HookMessageContract $message);
}