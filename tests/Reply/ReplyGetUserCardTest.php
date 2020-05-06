<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyGetUserCardTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }


    public function test_receive()
    {
        $response = $this->makeReply('reply_receive_card_pl');
        $this->assertEquals(true, true);
    }

}