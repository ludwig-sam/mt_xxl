<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyImageTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }


    public function test_image()
    {
        $response = $this->makeReply('reply_image');

        $this->assertEquals(true, true);
    }


}