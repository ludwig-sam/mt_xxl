<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway\Test;



use App\Service\Gateway\RistictService;
use App\Service\Gateway\TestHelper;
use Tests\TestCase;

class RistictServiceTest extends TestCase
{


    public function test_risk()
    {
        $ristrict_service = new RistictService();


        $ip_service = new TestHelper\IpService();

        $ip_service->setIp('127.0.0.3');

        $ip_risk    = new TestHelper\IpRistrict($ip_service);

        $ip_risk->setBlackList([
            '127.0.0.3'
        ]);

        $ristrict_service->registe($ip_risk);

        $this->assertFalse($ristrict_service->isPass());
    }

}