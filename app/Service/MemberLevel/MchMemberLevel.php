<?php

namespace App\Service\MemberLevel;


use Libs\Log;
use Libs\Unit;
use App\Models\MchMemberLevelModel;
use App\Models\MemberlevelModel;


class MchMemberLevel
{

    private $mchId;
    private $level;


    public function __construct($mchId, $level)
    {
        $this->mchId = $mchId;
        $this->level = $level;
    }

    public function discount($price)
    {
        $mchMemberLvModel = new MchMemberLevelModel();
        $level = $mchMemberLvModel->where('mch_id', $this->mchId)->where('member_level_id', $this->level)->first();

        if(!$level){
            return $price;
        }

        return Unit::fentoYun(floor($price * $level['discount'] * 100));
    }

    private function getDefaultPoint()
    {
        $mchMemberLvModel = new MemberlevelModel();

        return (int)$mchMemberLvModel->where('level', $this->level)->value('default_point');
    }

    public function getLevels()
    {
        $mchMemberLvModel = new MchMemberLevelModel();

        return $mchMemberLvModel->where('mch_id', $this->mchId)->get();
    }

    public function getUpdatePoint($price)
    {
        $mchMemberLvModel = new MchMemberLevelModel();
        $level = $mchMemberLvModel->where('mch_id', $this->mchId)->where('member_level_id', $this->level)->first();

        if(!$level){
            return $this->getDefaultPoint();
        }

        $consume = $level['consume'];

        if($consume <= 0){
            return 0;
        }

        $floor = floor($price / $consume);

        return $floor * max($level['point'], 0);
    }
}