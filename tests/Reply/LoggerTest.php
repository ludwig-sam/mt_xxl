<?php namespace Tests\Reply;

use App\Service\Wechat\Conditions\Eque;
use App\Service\Wechat\Conditions\Greater;
use App\Service\Wechat\Conditions\Less;
use App\Service\Wechat\Conditions\Preg;
use Providers\ReplyReceiveMessage;
use App\Service\Wechat\Reply;
use Tests\TestCase;

class LoggerTest extends TestCase{

    public function test_warning(){

        app()->get('sls.writer')->info('测试app.logger', [
            'desc' => 'this is  logger test '
        ]);

        $this->assertTrue(true);
    }

}