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

class CustomerTypesTest extends TestCase
{

    private function make($text) : MessageInterface
    {
        Factory::make('wechatCustomer');
        return Factory::message('openid', $text);
    }

    public function test_text(){

        $message = $this->make('text');

        $message->setMessage('openid_1', [
            "content" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => 'openid_1',
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
            'touser' => 'openid_1',
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
            'touser' => 'openid_1',
            'msgtype' => 'video',
            'video'   => [
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
            'touser' => 'openid_1',
            'msgtype' => 'music',
            'music'   => [
                 'title' => 'MUSIC_TITLE',
                 'description' => 'MUSIC_DESCRIPTION',
                 'musicurl' => 'MUSIC_URL',
                 'hqmusicurl' => 'HQ_MUSIC_URL',
                 'thumb_media_id' => 'THUMB_MEDIA_ID',
            ]
        ], $message->getContent());
    }


    public function test_news(){
        $message = $this->make('news');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => 'openid_1',
            'msgtype' => 'news',
            'news'   => [
                "articles"=>[
                    [
                        "title"        =>"Happy Day",
                        "description"  =>"Is Really A Happy Day",
                        "url"          =>"URL",
                        "picurl"       =>"PIC_URL"
                    ],
                    [
                        "title"        =>"Happy Day",
                        "description"  =>"Is Really A Happy Day",
                        "url"          =>"URL",
                        "picurl"       =>"PIC_URL"
                    ]
                ]
            ]
        ], $message->getContent());
    }

    public function test_mpnews(){
        $message = $this->make('mpnews');

        $message->setMessage(['openid_1', 'open_id2'], [
            "media_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => 'openid_1',
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
            'touser' => 'openid_1',
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
            'touser' => 'openid_1',
            'msgtype' => 'wxcard',
            'wxcard'   => [
                "card_id" => '123dsdajkasd231jhksad'
            ]
        ], $message->getContent());
    }

    public function test_miniprogrampage(){
        $message = $this->make('miniprogrampage');

        $message->setMessage(['openid_1', 'open_id2'], [
            "card_id" => "123dsdajkasd231jhksad"
        ]);

        $this->assertEquals([
            'touser' => 'openid_1',
            'msgtype' => 'miniprogrampage',
            'miniprogrampage'   => [
                "title"=>"title",
                "appid"=>"appid",
                "pagepath"=>"pagepath",
                "thumb_media_id"=>"thumb_media_id"
            ]
        ], $message->getContent());
    }

}