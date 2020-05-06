<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/16
 * Time: 下午4:36
 */

namespace Tests\MemberLevel;


use App\Models\MemberModel;
use App\Service\MemberLevel\MemberLevelUpgrade;
use App\Service\Users\Mook\MemberUserMook;
use Tests\TestCase;

class MemberlvUpdateTest extends TestCase
{

    public function test_update()
    {
        $member_upgrade_service = new MemberLevelUpgrade(MemberUserMook::getInstance());

        $meber_model = new MemberModel();

        $meber_row   = $meber_model->find(MemberUserMook::getInstance()->getId());

        $member_upgrade_service->update();

        $this->assertEquals(2, $meber_row->value('level'));
    }
}