<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 上午10:51
 */

namespace Tests\Sms;


use App\Service\Sms\SmsVerifyCode;
use Tests\TestCase;

class SendTest extends TestCase
{

    public function test_send()
    {
        $service = new SmsVerifyCode();
        try{
            $this->assertEquals(true, $service->send(13127503298));
        }catch (\Exception $exception){
            $this->assertEquals("请稍后重试", $exception->getMessage());
        }
    }

}