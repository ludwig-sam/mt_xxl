<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 下午1:34
 */

namespace Tests\Message;


use App\Service\MessageSend\Factory;
use App\Service\MessageSend\Methods\WechatCustomer;
use Tests\TestCase;

class SendTest extends TestCase
{

    public function test_makeSender(){
       $sender =  Factory::make("wechatCustomer");

       $this->assertEquals(new WechatCustomer(), $sender);
    }


    public function test_makeMessageTag(){

        try{

            Factory::make("wechatCustomer");

            $message = Factory::message('tag', 'text');

        }catch (\Exception $exception){
            $this->assertEquals("不支持的过滤方式：App\Service\MessageSend\Methods\wechatCustomer.tag", $exception->getMessage());
        }
    }

}