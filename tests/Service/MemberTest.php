<?php namespace Tests\Reply;

use App\Models\MemberInterestModel;
use App\Service\Member\Member;
use Tests\TestCase;

class MemberTest extends TestCase{


    private function service(){

        return new Member();
    }

    public function test_addInterest(){

        $service = $this->service();

        $ret = $service->addInterest(1, [1,2,"blue"]);

        $service->addInterest(2, [1,3,0]);

        $this->assertTrue($ret);

        $memberIntrestModel = new MemberInterestModel();

        $list = $memberIntrestModel->where('member_id', 1)->get()->toArray();

        $this->assertEquals([1,2], array_column($list, 'mch_category_id'));

    }


}
