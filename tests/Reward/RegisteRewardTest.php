<?php


namespace Tests\Reward;


use App\DataTypes\OutStrTypes;
use App\Service\Reply\Receive;
use Tests\TestCase;

class RegisteRewardTest extends TestCase
{

    private function xml()
    {
        return [
            'MsgType'      => 'event',
            'Event'        => 'user_get_card',
            'CardId'       => $this->getWxCardId(),
            'FromUserName' => $this->getOpenid(),
            'OuterStr' => OutStrTypes::outer_str_registe_reward,
        ];
    }

    public function test_receive()
    {
        $response = Receive::responseOriginalMsg($this->xml());

        $this->assertEquals(true, true);
    }

}