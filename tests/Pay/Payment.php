<?php namespace Tests\Pay;

use Illuminate\Support\Collection;
use Libs\Pay;
use Tests\TestCase;

class Payment extends TestCase
{


    public function test_wechatPay()
    {

        try {
            $param = new Collection(["total_fee" => 0.01, 'order_no' => 'tewtadassdfk2233223']);
            $obj   = Pay::payment('wechat', ['key' => 'test_key'])->pay('micro', $param);
        } catch (\Exception $exception) {
            $this->assertEquals('Get Wechat API Error:mch_id参数格式错误', $exception->getMessage());
        }

        $this->assertTrue(true);
    }


    public function test_upayPay()
    {

        try {
            $param = ["pay" => 0, "id" => null, "empty" => "", 'total_amount' => 0.01, 'amount' => 0.01, 'order_no' => 'tewtadassdfk2233223', 'auth_code' => 'this is mook auth_code', 'subject' => '收卷机'];

            $payment = Pay::payment('upay',
                [
                    'key'         => 'EFA89FBFAC6940B4BAB9970E7A4B0E41',
                    'merchant_id' => '898331189990591',
                    'terminal_id' => '11324800'

                ]
            )->pay('microWechat', new Collection($param));
        } catch (\Exception $exception) {
            $this->assertEquals('Upay API Error:授权码异常', $exception->getMessage());
        }
    }

}