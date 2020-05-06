<?php

namespace App\Service\Auth\Test;


use App\Service\Auth\MemberCode;
use Tests\TestCase;

class OnePasswordTest extends TestCase
{


    public function test_build()
    {
        $one_pwd = new MemberCode();

        $uid = 110;

        $key = $one_pwd->encode($uid);

        $this->assertGreaterThan(10, strlen($key));

        $this->assertEquals($uid, $one_pwd->decode($key));
    }

}