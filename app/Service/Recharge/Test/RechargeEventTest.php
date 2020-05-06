<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/31
 * Time: 下午7:38
 */

namespace App\Service\Recharge\Test;


use App\Service\Row\RechargeRow;
use Tests\TestCase;

class RechargeEventTest extends TestCase
{

    public function testNotify()
    {
        $order_id = 56;
        $order    = new RechargeRow($order_id);

        systemEvent($order, 'recharge');

        $this->assertTrue(true);
    }
}