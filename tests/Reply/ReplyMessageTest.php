<?php namespace Tests\Reply;

use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ReplyMessageTest extends TestCase{


    public function testSetArr(){
        $arr = [
            'name' => 'blue',
            'age' => 24,
            'desc' => '是我想太多'
        ];

        $container = new ReplyReceiveMessage($arr);
        $this->assertEquals($arr, $container->toArray());

    }

    public function testObject(){
        $objStr  = '{"name" : "green", "age" : 20, "sex" : 2}';
        $obj     = json_decode($objStr);

        $this->assertTrue(is_object($obj));

        $arr     = json_decode($objStr ,true);

        $this->assertTrue(is_array($arr));

        $container = new ReplyReceiveMessage($arr);

        $objContainer = new ReplyReceiveMessage($obj);

        $this->assertEquals($arr, (new ReplyReceiveMessage($obj))->toArray());

        $this->assertEquals($arr , $container->toArray());

        $this->assertEquals($arr , $container->toArray());


    }

    public function test_xml()
    {
        $xml = '<xml>
    <ToUserName><![CDATA[gh_611db32b9272]]></ToUserName>
    <FromUserName><![CDATA[oZy2G1fT7FwC9kJd11qq6zgYb6mE]]></FromUserName>
</xml>';

        $msg = new ReplyReceiveMessage($xml);

        $this->assertEquals('oZy2G1fT7FwC9kJd11qq6zgYb6mE', $msg->getAttr('FromUserName'));

        $msg->setAttr('FromUserName', '-');

        $this->assertEquals('-', $msg->getAttr('FromUserName'));

        $this->assertEquals([
            'ToUserName' => 'gh_611db32b9272',
            'FromUserName' => '-'
        ], $msg->toArray());

        $msg->setAttr('time', '123333');

        $this->assertEquals('123333', $msg->getAttr('time'));
    }





}