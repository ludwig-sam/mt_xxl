<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use App\Service\Wechat\Reply;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyHookTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }

    public function test_pushSystemHooks()
    {
        $xml = '<xml>
    <MsgType><![CDATA[event]]></MsgType>
    <Event><![CDATA[user_get_card]]></Event>
</xml>';
        $reply = new Reply(new ReplyReceiveMessage($xml));

        $hooks = [
            [
                'name'     => 'exchange_success',
                'is_async' => 0,
                'delay'    => 0,
                'condition_op' => 'reg_exp',
                'condition_key' => 'OuterStr',
                'condition_val' => '/^card_receive_exchange/',
            ]
        ];

        $this->assertEquals([
            [
                'name'     => 'exchange_success',
                'is_async' => 0,
                'delay'    => 0,
                'condition_op' => 'reg_exp',
                'condition_key' => 'OuterStr',
                'condition_val' => '/^card_receive_exchange/',
            ],
            [
                'name' => 'receive_card',
                'is_async' => 0,
                'delay'    => 0,
                'condition_op' => null,
                'condition_key' => null,
                'condition_val' => null,
            ]
        ] , $reply->pushSystemHooks($hooks));

    }




}