<?php namespace App\Service\Member;

use App\Exceptions\MemberException;
use App\Http\Codes\Code;
use App\DataTypes\RewardsStatus;


class RegisteReward extends Reward {


    public function getRow()
    {
        return $this->model()->getReword(RewardsStatus::event_register, $this->user->getId());
    }

    public function check($row)
    {
        if(!$row){
            throw new MemberException("注册奖励不存在", Code::registe_reward_not_exist);
        }
    }

    public function isReceive($row)
    {
        if(!$row)return false;

        return $row->status == RewardsStatus::status_receive;
    }

    public function add($card_id)
    {
        return $this->model()->addReward(RewardsStatus::event_register, $this->user->getId(), $card_id);
    }

    public function getCardId($row)
    {
        return $row->card_id;
    }

    public function receive()
    {
        $row = $this->getRow();

        $this->check($row);

        return $row->update([
            'status' => RewardsStatus::status_receive
        ]);
    }

    public function getConfig($card_id)
    {
        $config_service = new RewardConfig();

        return $config_service->get(RewardsStatus::event_register, $card_id);
    }

}