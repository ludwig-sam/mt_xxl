<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 下午4:27
 */

namespace App\Service\Auth\Test;


use App\Service\Auth\SignService;
use Tests\TestCase;

class SignTest extends TestCase
{

    public function test_sign()
    {
        $get = [
            "g1" => null,
            "g2" => "test"
        ];

        $post = [
            "p3" => "bl",
            "p2" => "nihao",
            "p1" => [
                "color" => "green"
            ]
        ];

        $all            = array_merge($get, $post);

        $sign_serservie = new SignService();

        $sign = $sign_serservie->mySign($all, 'test');

        $this->assertEquals('44f431a16e3960f41b3cd28763e15d66', $sign);
    }

}