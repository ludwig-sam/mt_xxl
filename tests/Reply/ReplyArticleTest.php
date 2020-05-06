<?php namespace Tests\Reply;

use App\Service\Reply\Receive;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyArticleTest extends TestCase{

    private function makeReply($xml){
        return Receive::responseOriginalMsg(file_get_contents(__DIR__ . '/data/' .  $xml . '.xml'));
    }


    public function test_image()
    {
        $response = $this->makeReply('reply_article');

        $this->assertEquals(true, true);
    }


}