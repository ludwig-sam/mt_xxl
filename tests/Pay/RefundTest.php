<?php namespace Tests\Pay;

use App\Exceptions\PayApiException;
use Libs\Pay;
use Tests\TestCase;

class RefundTest extends TestCase{



    public function test_refundFail(){
        $orderInfo = array (
           'id' => 111,
           'mch_id' => 2,
           'store_id' => 2,
           'cashier_id' => 17,
           'order_no' => '1529829213736',
           'member_id' => 5,
           'transaction_id' => '11324800180624171239564674',
           'payment_id' => 13,
           'payment_name' => '银联微信刷卡支付',
           'channel' => 'upay_wx_pub_bar_code',
           'total_amount' => '0.01',
           'amount' => '0.01',
           'refund_amount' => '0.00',
           'subject' => 'test',
           'auth_code' => '134512911834345760',
           'attach' => '812677539926',
           'status' => 'PENDING',
           'payment_time' => 0,
           'refund_time' => 0,
           'created_at' => '2018-06-24 17:12:36',
           'updated_at' => '2018-06-24 17:12:36',
           'refund_no' => Pay::orderNo()
        );
        $configParam = ["terminal_id" => 11324800, "merchant_id" => "898331189990591","key" => "EFA89FBFAC6940B4BAB9970E7A4B0E41"];

        $way   = 'upay';
        $type  = 'MicroWechat';


        try{
            $result = Pay::payment($way, $configParam)->refund($type, $orderInfo);

        }catch (PayApiException $exception){
            $this->assertEquals('Upay API Submessage:商户不支持隔日退货', $exception->getMessage());
        }

    }

    public function test_refundSuccess(){
        $orderInfo = array (
            'order_no' => '1529891571686',
            'transaction_id' => '',
            'total_amount' => '0.01',
            'amount' => '0.01',
            'refund_amount' => '0.00',
            'refund_no' => Pay::orderNo()
        );

        $orderInfo['refund_amount'] = $orderInfo['amount'];

        $configParam = ["terminal_id" => 11324800, "merchant_id" => "898331189990591","key" => "EFA89FBFAC6940B4BAB9970E7A4B0E41"];

        $way   = 'upay';
        $type  = 'MicroWechat';

        try{
            $result = Pay::payment($way, $configParam)->refund($type, $orderInfo);
            $this->assertEquals($result->get("refund_amount"), $orderInfo['refund_amount']);
        }catch ( PayApiException $exception){
            $this->assertEquals("Upay API Submessage:退款金额超限或交易已撤销", $exception->getMessage());
        }

    }

}