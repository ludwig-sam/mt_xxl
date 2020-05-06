<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/16
 * Time: 下午4:30
 */

namespace App\Service\Users\Mook;


use App\Models\MemberModel;
use App\Service\Users\Contracts\SingleTrait;
use App\Service\Users\Contracts\UserAbstraict;

class MemberUserMook extends UserAbstraict
{
    use SingleTrait;

    public function getId()
    {
        return 5;
    }

    public function getMchId()
    {
        return 2;
    }

    public function isMember()
    {
        return true;
    }

    public function setId($id)
    {
    }

    public function setMchId($mchId)
    {
    }

    public function model()
    {
        return (new MemberModel())->find(5);
    }
}