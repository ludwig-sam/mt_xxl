<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午3:57
 */

namespace App\Service\Wechat\Hook\Contracts;


use Abstracts\ReplyMessageInterface;

interface HookInterface
{
    function hanlder(ReplyMessageInterface $msgObj);
}