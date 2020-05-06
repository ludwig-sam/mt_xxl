<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/11/1
 * Time: 上午10:49
 */

namespace App;


use Tests\TestCase;

class TestPayConfig extends TestCase
{

    public function test_matchByAuthCode()
    {
        $wx_auth_code = 134625482131070824;
        $al_auth_code = 282050744627162859;
        $ba_auth_code = 342050074215493090;
        $way          = PayConfig::way_upay;

        $this->assertEquals('upay_wx_pub_bar_code', PayConfig::matchByAuthCode($way, $wx_auth_code));
        $this->assertEquals('upay_alipay_bar_code', PayConfig::matchByAuthCode($way, $al_auth_code));
        $this->assertEquals('balance_pay', PayConfig::matchByAuthCode($way, $ba_auth_code));
    }

    public function test_isSystemCode()
    {
        $auth_code_short = 342050079;
        $auth_code_long  = 34205007421530909;

        $this->assertTrue(PayConfig::isSystemCode($auth_code_short));
        $this->assertTrue(PayConfig::isSystemCode($auth_code_long));
    }

    public function test_matchPaymentId()
    {
        $ba_auth_code = 342050074215493090;

        $this->assertEquals('balance', PayConfig::matchCodeType($ba_auth_code));
    }
}