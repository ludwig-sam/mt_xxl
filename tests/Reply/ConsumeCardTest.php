<?php

namespace Tests\Reply;


use App\Service\Reply\Receive;
use Tests\TestCase;

class ConsumeCardTest extends TestCase
{


    public function test_log()
    {
        $xml = '<xml>
<ToUserName><![CDATA[gh_611db32b9272]]></ToUserName>
<FromUserName><![CDATA[oZy2G1QwwhOrbTK_O0hpExevbXho]]></FromUserName>
<CreateTime>1532498411</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[user_consume_card]]></Event>
<CardId><![CDATA[pZy2G1dyHRbtmY2DgNV824kj_kZQ]]></CardId>
<UserCardCode><![CDATA[446560313260]]></UserCardCode>
<ConsumeSource><![CDATA[FROM_API]]></ConsumeSource>
<LocationName><![CDATA[]]></LocationName>
<StaffOpenId><![CDATA[oZy2G1R1Ell6w88CjYctRkanE1gM]]></StaffOpenId>
<VerifyCode><![CDATA[]]></VerifyCode>
<RemarkAmount><![CDATA[]]></RemarkAmount>
<OuterStr><![CDATA[outer_str_card_consume_exe_consume:28_22]]></OuterStr>
<LocationId>0</LocationId>
</xml>';

        Receive::responseOriginalMsg($xml);

        $this->assertEquals(true, true);
    }

}