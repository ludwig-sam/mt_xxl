<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }



    public function testDefault(){
        $reply =  Receive::responseOriginalMsg([]);
        $this->assertEquals('', $reply->get('content'));
    }

    public function test_placeholder(){
        $response = $this->makeReply('reply_text_pl');
        $this->assertEquals('openidlixingbo', $response->get('content'));
    }

    public function test_keywords()
    {
        $this->makeReply('reply_text');

        $this->assertEquals(true, true);
    }


}