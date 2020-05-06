<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午4:58
 */

namespace Tests\Wechat;


use App\Service\Fans\Updating;
use App\Service\Wechat\Message\HookMessage;
use App\Service\Wechat\User;
use Providers\ReplyReceiveMessage;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_get()
    {

        $user_service = new User();
        $openid  = 'oAWKk0aoJrGwDMvdfmR-rb6mWeIY';
        $user_data  = $user_service->get($openid);

        $this->assertEquals($openid, $user_data->get('openid'));

    }

    public function test_updating()
    {
        $updating = new Updating();

        $updating->update();

        $this->assertEquals('', '');

    }

}