<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/31
 * Time: 下午8:09
 */

namespace Libs\Payments\Special\Test;


use Libs\Payments\Special\Support\Support;
use Tests\TestCase;

class SignTest extends TestCase
{

    function testSign()
    {
        $data = [
            'sign'     => 'test',
            'order_id' => 1,
            'is_md5'   => true,
            'money'    => 20.22,
            'subject'  => '测试',
            'empty'    => '',
            'other'    => [
                [
                    'gid'   => 1,
                    'title' => '饮料'
                ]
            ]
        ];


        $expected = 'is_md5=true&money=20.22&order_id=1&sign=test&subject=测试';

        $this->assertEquals($expected, Support::getSignContent($data));
    }

    function testSignEncode()
    {
        $key = 'ablucdd82832';

        $data = [
            'sign'     => 'test',
            'order_id' => 1,
            'is_md5'   => true,
            'money'    => 20.22,
            'subject'  => '测试',
            'empty'    => '',
            'other'    => [
                [
                    'gid'   => 1,
                    'title' => '饮料'
                ]
            ]
        ];

        $expected = 'ad662cdbc67b8e6ec5576b1e528855fe';
        //        $expected = strtoupper($expected);

        $this->assertEquals($expected, Support::generateSign($data, $key));
    }
}