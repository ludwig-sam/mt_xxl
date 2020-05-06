<?php namespace Tests\Pay;

use Libs\Payments\Upay\Support\Support;
use Tests\TestCase;

class UnitTest extends TestCase{



    public function test_sign(){
       $arr = array (
            'merchant_id' => '898331189990591',
            'version' => '1.4',
            'terminal_id' => 11324800,
            'sign_type' => 'MD5',
            'timestamp' => '2018-06-22 17:11:01',
            'request_id' => '11324800171101',
            'term_request_id' => '113248001711010661',
            'id' => 12,
            'mch_id' => 2,
            'store_id' => 2,
            'cashier_id' => 17,
            'order_no' => '1805061357465746146912',
            'member_id' => 0,
            'transaction_id' => '',
            'payment_id' => 0,
            'payment_name' => '',
            'channel' => 'upay_wx_pub_bar_code',
            'total_amount' => 0,
            'amount' => 0,
            'refund_amount' => 0,
            'subject' => 'test',
            'auth_code' => '134902561972020819',
            'attach' => '812677539926',
            'status' => 0,
            'payment_time' => 0,
            'refund_time' => 0,
            'created_at' => '2018-06-22 17:11:01',
            'updated_at' => '2018-06-22 17:11:01',
            'biz_channel' => 'umszj.channel.wxpay',
            'biz_type' => 'umszj.trade.pay',
            'biz_content' => '{"ext_no":"1805061357465746146912","auth_code":"134902561972020819","subject":"test","total_amount":0,"currency":"CNY"}'
        );

        $newSign = Support::generateSign($arr, 'EFA89FBFAC6940B4BAB9970E7A4B0E41');

        $sign = "5CA33E80D7D4E7E9801E8089C87D2180";

        $this->assertEquals($sign, $newSign);
    }

}