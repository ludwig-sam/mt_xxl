<?php namespace App\Service\Users;


use App\Service\Users\Contracts\SingleTrait;
use App\Service\Users\Contracts\UserAbstraict;

class MemberUser extends UserAbstraict{
    use SingleTrait;

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getMchId()
    {
        return 0;
    }

    public function setMchId($mchId)
    {
        // TODO: Implement setMchId() method.
    }

    public function isMember()
    {
        return $this->is_member == 1;
    }

    public function setId($id)
    {
        $this->setAttribute('id', $id);
    }

}