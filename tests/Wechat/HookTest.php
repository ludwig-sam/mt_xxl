<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午4:58
 */

namespace Tests\Wechat;


use App\Service\Wechat\Message\HookMessage;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class HookTest extends TestCase
{
    public function test_spellingHookNmae()
    {
        $message     = new ReplyReceiveMessage([]);
        $hookMessage = new HookMessage($message);

        $this->assertEquals('App\\Service\\Wechat\\Hook\\TestHook', $hookMessage->spellingHookClassName('test'));

    }
}