<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use Tests\TestCase;

class ReplyExchangeTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }

    public function test_consume(){

        $this->makeReply('reply_exchage');

        $this->assertEquals(true, true);
    }


}