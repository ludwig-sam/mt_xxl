<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/1
 * Time: 上午10:22
 */

namespace Providers\Payments\Wechat\Test;


use Libs\Payments\Wechat\Support\JsApi;
use Tests\TestCase;

class SignTest extends TestCase
{

    function testSign()
    {
        $param = [
            'package'   => 'prepay_id=wx2017033010242291fcfe0db70013231072',
            'timeStamp' => '1490840662',
            'nonceStr'  => '5K8264ILTKCH16CQ2502SI8ZNMTM67VS',
            'appId'     => 'wxd678efh567hg6787',
            'signType'  => 'MD5',
        ];

        $key = 'qazwsxedcrfvtgbyhnujmikolp111111';

        $jsApi = new JsApi();
        $sign  = $jsApi->sign($param, $key);

        $this->assertEquals('appId=wxd678efh567hg6787&nonceStr=5K8264ILTKCH16CQ2502SI8ZNMTM67VS&package=prepay_id=wx2017033010242291fcfe0db70013231072&signType=MD5&timeStamp=1490840662&key=qazwsxedcrfvtgbyhnujmikolp111111', $jsApi->getSignString());

        $this->assertEquals('22D9B4E54AB1950F51E0649E8810ACD6', $sign);
    }

}