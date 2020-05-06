<?php

namespace App\Service\MemberLevel;


use Libs\Arr;
use Libs\Log;
use App\Models\MemberlevelModel;
use App\DataTypes\MessageSendRoots;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Users\Contracts\UserAbstraict;

class MemberLevelUpgrade implements MessageProviderInterface
{

    private $member;

    private $member_row;

    public function __construct(UserAbstraict $user)
    {
        $this->member = $user;
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::member_level_notify;
    }

    public function getMessageTo()
    {
        $openid = $this->member->getAttribute('openid');

        if(!$openid) return [];

        return [$openid];
    }

    public function getMessageParam()
    {
        return $this->member->toArray();
    }

    private function getMember()
    {
        if(!$this->member_row){
            $this->member_row = $this->member->model()->where('id', $this->member->getId())->first();
        }

        return $this->member_row;
    }

    private function isSatisfy($level_row)
    {
        if($this->getMember()->level >= $level_row->level){
            return false;
        }

        return $this->getMember()->exp >= $level_row['exp'];
    }

    private function getMemberLevels()
    {
        $member_level_model = new MemberlevelModel();

        return $member_level_model->orderBy('level', 'desc')->get()->toArray();
    }

    private function getCurLevelIndex(&$levels)
    {
        $levels[] = ['id' => 0, 'exp' => $this->getMember()->exp];

        usort($levels, function($v1, $v2){
            return $v1['exp'] - $v2['exp'];
        });

        return Arr::findIndex($levels, 0);
    }

    private function getNewLevel($levels, $cur_index)
    {
        if(!isset($levels[$cur_index - 1])){
            $level_info = $levels[$cur_index + 1];
        }else{
            $level_info = $levels[$cur_index - 1];
        }

        return $level_info['level'];
    }

    public function update()
    {
        $levels = $this->getMemberLevels();

        $cur_index = $this->getCurLevelIndex($levels);

        if($cur_index === null) return;

        $old_level = $this->getMember()->level;

        $new_level = $this->getNewLevel($levels, $cur_index);

        if($new_level == $this->getMember()->level){
            return;
        }

        $this->getMember()->update(['level' => $new_level]);

        $this->member->setAttribute('old_level', $old_level);
        $this->member->setAttribute('level', $new_level);

        MessageTirgger::instance()->trigger($this);
    }
}