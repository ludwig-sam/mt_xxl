<?php namespace Tests\Pay;

use Libs\Pay;
use App\PayConfig;
use Tests\Providers\RequestMook;
use Tests\TestCase;

class Dopay extends TestCase{



    public function test_mathAuthCode(){

        $authCode = 134934732502275631;

        $payService = new \App\Service\Pay\Payment();

        $this->assertEquals(PayConfig::PAYMENTS[PayConfig::PAYMENT_WX_BAR_CODE], $payService->matchChannelByAuthCode('offical', $authCode));

        $this->assertEquals(PayConfig::PAYMENTS[PayConfig::PAYMENT_UPAY_WX_BAR_CODE], $payService->matchChannelByAuthCode('upay', $authCode));

    }

    public function test_checkPay(){

        $request = new RequestMook();
        $service = new \App\Service\Pay\Payment();

        $request->offsetSet('mch_id', 1);
        $request->offsetSet('auth_code', 1349347325022756311);

        try{
            $this->assertEquals('', $service->checkPay($request));
        }catch (\Exception $exception){
            $this->assertEquals('无效的支付码: 1349347325022756311', $exception->getMessage());
        }

    }

    public function test_checkPayConfig(){
        $request = new RequestMook();
        $service = new \App\Service\Pay\Payment();

        $request->offsetSet('mch_id', 1);
        $request->offsetSet('auth_code', 134934732502275631);

        try{
            $this->assertArrayHasKey('mch_id', $service->checkPay($request)->config_param);

            $service->checkPay($request)['config_param'];
        }catch (\Exception $exception){
            $this->assertEquals('商户支付配置错误:wx_pub_bar_code', $exception->getMessage());
        }
    }


    public function test_calculationAmountNotExists(){
        $request = new RequestMook();
        $service = new \App\Service\Pay\Payment();

        $request->offsetSet('total_amount', 10);
        $request->offsetSet('coupon_id', -1);
        $request->offsetSet('coupon_code', 'this is test code');

        try{
            $service->calculationAmount($request);
        }catch (\Exception $exception){
            $this->assertEquals('卡券不存在', $exception->getMessage());
        }

    }

    public function test_calculationAmount(){
        $request = new RequestMook();
        $service = new \App\Service\Pay\Payment();

        $request->offsetSet('total_amount', 10);
        $request->offsetSet('coupon_id', 53);
        $request->offsetSet('coupon_code', 'this is test code');

        try{

            $this->assertEquals(9 , $service->calculationAmount($request));

        }catch (\Exception $exception){
            $this->assertEquals('卡券不存在', $exception->getMessage());
        }

    }


}