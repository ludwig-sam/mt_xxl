<?php namespace App\Service\Member;


use App\Service\Service;
use App\Service\Users\MemberUser;

class MemberActor extends Service implements Actable {

    private $state;
    private $stateIsMember;
    private $stateIsNotMember;
    private $stateNotExists;

    public function __construct()
    {
        $this->stateIsMember    = new MemberIsMem($this);
        $this->stateIsNotMember = new MemberIsNotMem($this);
        $this->stateNotExists   = new MemberNotExists($this);

        switch (true){
            case !MemberUser::getInstance()->getId():
                $this->state = $this->stateNotExists;
                break;
            case !MemberUser::getInstance()->isMember():
                $this->state = $this->stateIsNotMember;
                break;
            default:
                $this->state = $this->stateIsMember;
                break;
        }
    }

    public function becomeMember($memberData)
    {
        return $this->state->becomeMember($memberData);
    }


}