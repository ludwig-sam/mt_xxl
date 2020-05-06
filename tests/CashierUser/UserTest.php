<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/7
 * Time: 下午3:16
 */

namespace Tests\CashierUser;


use App\Service\Users\CachierUser;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_login()
    {
        CachierUser::getInstance()->init([1,2,3,4]);

       $this->assertEquals(4,  CachierUser::getInstance()->getAttribute('exe_id'));
    }

}