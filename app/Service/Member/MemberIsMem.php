<?php namespace App\Service\Member;


class MemberIsMem implements Actable {

    private $actor;
    public function __construct(MemberActor &$memberActor)
    {
        $this->actor = $memberActor;
    }

    public function becomeMember($memberData)
    {
        return false;
    }

}