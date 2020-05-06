<?php

namespace Tests\Reply;


use App\Service\Wechat\Card;
use Tests\Api\ApiBase;

class CardSignatureTest extends ApiBase
{

    public function test_ext(){

        $cardId = 'pAWKk0YAIbNa-lsT8A_JuE2znb-4';

        $pamam = [
            'timestamp' => 1530346716,
            'nonce_str' =>  '1530346716ojgaxQFL4fP9MzW8Cq'
        ];

        $cardService = new Card();

        $this->assertEquals('3c8ed118f884bce7fb69d44ef1b790a56b0b88c3', $cardService->cardExtSignature($cardId, $pamam, '9KwiourQPRN3vx3Nn1c_iamw1gw2JlUfpC5hKmPNml_4kjtUbMTxtuwyhaJJdseRD2_11zeHunCA53-290bJrw'));

    }

}