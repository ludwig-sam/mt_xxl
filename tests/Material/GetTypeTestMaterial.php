<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午2:15
 */

namespace Tests\Material;


use App\Service\Material\Contracts\GetTypeTrait;
use Tests\TestCase;

class GetTypeTestMaterial extends TestCase
{
    use GetTypeTrait;

    public function test_getType()
    {
        $this->assertEquals('get_type_test', self::getType());
    }

}