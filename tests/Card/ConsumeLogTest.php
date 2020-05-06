<?php

namespace Tests\Reply;


use App\DataTypes\OutStrTypes;
use App\Models\PayCardConsumeLog;
use App\Models\PayOrderModel;
use App\Service\Wechat\Card;
use App\Service\Wechat\Hook\ExeConsumeCardLogHook;
use App\Service\Wechat\Hook\PaySuccessConsumeCardHook;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class ConsumeLogTest extends TestCase
{

    public function test_payLog()
    {
        $order_id = 494;

        $pay_order_model = new PayOrderModel();

        $order_row = $pay_order_model->find($order_id);

        $order_detail = $order_row->hasOneDetail;

        $message = new ReplyReceiveMessage($order_row->toArray());

        $pay_consume_card_hook = new PaySuccessConsumeCardHook();

        $pay_consume_card_hook->setMessage($message);

        $pay_consume_card_hook->saveLog($order_detail->coupon_id, $order_detail->coupon_code, OutStrTypes::outer_str_card_consume_exe_pay);

        $consume_log_model = new PayCardConsumeLog();

        $log_row = $consume_log_model->where('order_no', $order_row->order_no)->first();

        $this->assertTrue(is_object($log_row));
    }

    public function test_log()
    {
        $pay_consume_card_hook = new ExeConsumeCardLogHook();

        $xml = '<xml>
<ToUserName><![CDATA[gh_611db32b9272]]></ToUserName>
<FromUserName><![CDATA[oZy2G1QwwhOrbTK_O0hpExevbXho]]></FromUserName>
<CreateTime>1532498411</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[user_consume_card]]></Event>
<CardId><![CDATA[pZy2G1X7Di7_NwYBrSFZIoqek09I]]></CardId>
<UserCardCode><![CDATA[946442692002]]></UserCardCode>
<ConsumeSource><![CDATA[FROM_API]]></ConsumeSource>
<LocationName><![CDATA[]]></LocationName>
<StaffOpenId><![CDATA[oZy2G1R1Ell6w88CjYctRkanE1gM]]></StaffOpenId>
<VerifyCode><![CDATA[]]></VerifyCode>
<RemarkAmount><![CDATA[]]></RemarkAmount>
<OuterStr><![CDATA[outer_str_card_consume_exe_consume:28_22]]></OuterStr>
<LocationId>0</LocationId>
</xml>';

        $message = new ReplyReceiveMessage($xml);

        $pay_consume_card_hook->hanlder($message);

        $this->assertEquals(true, true);
    }

}