<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 下午1:34
 */

namespace Tests\Message;


use App\Service\MessageSend\Contracts\MessageInterface;
use App\Service\MessageSend\Factory;
use Tests\TestCase;

class MassTypesTest extends TestCase
{

    private function make($text) : MessageInterface
    {
        Factory::make('wechatMass');
        return Factory::message('openid', $text);
    }

    public function test_text(){

        $message = $this->make('text');

        $message->setMessage(['openid_1'], [
            "content" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1'],
            'msgtype' => 'text',
            'text'   => [
                "content" => "123dsdajkasd231jhksad"
            ]
        ], $message->getContent());
    }

    public function test_image(){
        $message = $this->make('image');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'image',
            'image'   => [
                "media_id" => "123dsdajkasd231jhksad"
            ]
        ], $message->getContent());
    }

    public function test_video(){
        $message = $this->make('video');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'mpvideo',
            'mpvideo'   => [
                "media_id" => "123dsdajkasd231jhksad"
            ]
        ], $message->getContent());
    }

    public function test_music(){
        $message = $this->make('music');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'music',
            'music'   => [
                "media_id" => '123dsdajkasd231jhksad'
            ]
        ], $message->getContent());
    }


    public function test_news(){
        $message = $this->make('news');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'mpnews',
            'mpnews'   => [
                "media_id" => '123dsdajkasd231jhksad'
            ]
        ], $message->getContent());
    }

    public function test_voice(){
        $message = $this->make('voice');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'voice',
            'voice'   => [
                "media_id" => '123dsdajkasd231jhksad'
            ]
        ], $message->getContent());
    }

    public function test_card(){
        $message = $this->make('card');

        $message->setMessage(['openid_1', 'open_id2'], [
            "card_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => ['openid_1', 'open_id2'],
            'msgtype' => 'wxcard',
            'wxcard'   => [
                "card_id" => '123dsdajkasd231jhksad'
            ]
        ], $message->getContent());
    }
}