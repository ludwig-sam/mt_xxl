<?php namespace App\Service\Member;


class MemberNotExists implements Actable {

    private $actor;
    public function __construct(MemberActor &$memberActor)
    {
        $this->actor = $memberActor;
    }

    public function becomeMember($memberData)
    {
        $this->actor->setError( "会员不存在");
        return false;
    }

}