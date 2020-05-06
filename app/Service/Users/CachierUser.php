<?php namespace App\Service\Users;


use App\Service\Users\Contracts\SingleTrait;
use App\Service\Users\Contracts\UserAbstraict;

class CachierUser extends UserAbstraict{
    use SingleTrait;

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getMchId()
    {
        return $this->getAttribute('mch_id');
    }

    public function setMchId($mchId)
    {
        $this->setAttribute('mch_id', $mchId);
    }

    public function isMember()
    {
        return true;
    }

    public function setId($id)
    {
        $this->setAttribute('id', $id);
    }

    public function init($user)
    {
        $this->setMchId($user[0]);
        $this->setAttribute('store_id', $user[1]);
        $this->setId($user[2]);
        $this->setAttribute('exe_id', $user[3]);
    }

}