<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 下午1:34
 */

namespace Tests\Message;


use App\Service\MessageSend\Factory;
use App\Service\MessageSend\Helper\TemplateHelper;
use App\Service\MessageSend\Methods\WechatCustomer;
use Tests\TestCase;

class TemplateHelperTest extends TestCase
{

    public function test_parse()
    {
        $str = "{{first.DATA}}\n订单编号：{{keyword1.DATA}}\n支付时间：{{keyword2.DATA}}\n支付金额：{{keyword3.DATA}}\n{{remark.DATA}}";

       $this->assertEquals([
            [
                "name" => 'first',
                "title" => '',
                'value' => ''
            ],
           [
               "name" => "keyword1",
               "title" => "订单编号",
               "value" => ''
           ],
           [
               "name" => "keyword2",
               "title" => "支付时间",
               "value" => ''
           ],
           [
               "name" => "keyword3",
               "title" => "支付金额",
               "value" => ''
           ],
           [
               "name" => "remark",
               "title" => '',
               "value" => ''
           ]
       ], TemplateHelper::parse($str));
    }

    public function test_getName()
    {
        $this->assertEquals(['keyword1', '订单编号'], TemplateHelper::getName('订单编号：{{keyword1.DATA}}'));
    }

    public function test_getNameNoTitle()
    {
        $this->assertEquals(['keyword1', ''], TemplateHelper::getName('{{keyword1.DATA}}'));
    }



}