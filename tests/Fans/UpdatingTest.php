<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/12
 * Time: 上午11:18
 */

namespace Tests\Fans;


use App\Models\FansModel;
use App\Service\Wechat\User;
use Tests\TestCase;

class UpdatingTest extends TestCase
{

    public function test_next()
    {
        $fansModel = new FansModel();

        $db_list = $fansModel->orderBy("id", 'desc')->limit(2)->get()->toArray();

        $user_service = new User();
        $last_openid  = $db_list[1]['openid'];

        $openids      = $user_service->getOpenidList($last_openid)->get('openid');

        $next_openid  = $db_list[0]['openid'];

        $this->assertEquals($next_openid, $openids[0]);

    }

}