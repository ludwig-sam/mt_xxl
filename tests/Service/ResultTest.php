<?php namespace Tests\Reply;

use App\Service\Card\CardActor;
use Tests\TestCase;

class ResultTest extends TestCase{

    public function test_cardActor(){

        $cardState = new CardActor(['status' => CardActor::disabled]);

        $cardState->activate('aaa');

        $this->assertFalse($cardState->result->isSuccess());

        $this->assertEquals(null, $cardState->name);

        $cardState->setAttribute('name', 'istest');

        $this->assertEquals('istest', $cardState->name);
    }


}
