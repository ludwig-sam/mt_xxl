<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/11
 * Time: 下午2:18
 */

namespace Tests\Message;


use App\DataTypes\MessageSendRoots;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use Tests\TestCase;

class MessageTriggerTest extends TestCase implements MessageProviderInterface
{

    function getMessageTemplateName()
    {
        return MessageSendRoots::refund_notify;
    }

    function getMessageTo()
    {
        return ["oZy2G1fT7FwC9kJd11qq6zgYb6mE"];
    }

    function getMessageParam()
    {
        return ["reason" => "莫有原因", "refund_amount" => "一个亿"];
    }

    function testSend()
    {
        MessageTirgger::instance()->trigger($this);

        $this->assertTrue(true);
    }

}