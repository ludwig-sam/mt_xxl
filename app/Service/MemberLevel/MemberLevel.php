<?php

namespace App\Service\MemberLevel;


use App\Models\MemberlevelModel;
use App\Exceptions\MemberException;



class MemberLevel
{

    public function updateCheck($level_row, $exp, $consume)
    {
        $prev_level = $this->getRowByLv($level_row->level - 1);

        $nex_level  = $this->getRowByLv($level_row->level + 1);

        if($prev_level){
            if($exp <= $prev_level->exp){
                throw new MemberException("经验值必须大于" . $prev_level->exp);
            }

            if($consume <= $prev_level->consume){
                throw new MemberException("消费条件必须大于" . $prev_level->consume);
            }

        }

        if($nex_level){
            if($exp >= $nex_level->exp){
                throw new MemberException("经验值必须小于" . $nex_level->exp);
            }

            if($consume >= $nex_level->consume){
                throw new MemberException("消费条件必须小于" . $nex_level->consume);
            }
        }

    }

    private function getRowByLv($level)
    {
        return $this->model()->where('level', $level)->first();
    }

    private function model()
    {
        return new MemberlevelModel();
    }

    public function getRow($id)
    {
        $level_row = $this->model()->find($id);

        if(!$level_row){
            throw new MemberException("等级不存在", Code::not_exists);
        }

        return $level_row;
    }

}