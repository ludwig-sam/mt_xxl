<?php namespace App\Service\Users;


use App\Service\Users\Contracts\SingleTrait;
use App\Service\Users\Contracts\UserAbstraict;

class AdminUser extends UserAbstraict{
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
        $this->setAttribute("mch_id", $mchId);
    }

    public function isMember()
    {
        return true;
    }

    public function setId($id)
    {
        $this->setAttribute('id', $id);
    }

    public function isSuper()
    {
        if($this->getAttribute('is_super')){
            return true;
        }

        $temporary_mch_id = $this->getAttribute('temporary_mch_id');

        if($this->isAdminToMch($this->getMchId(), $temporary_mch_id)){
            return true;
        }

        return false;
    }

    public function isMchUser()
    {
        return $this->getMchId() || $this->getAttribute('temporary_mch_id');
    }

    private function isAdminToMch($mch_id, $temporary_mch_id)
    {
        return !$mch_id && $temporary_mch_id;
    }
}