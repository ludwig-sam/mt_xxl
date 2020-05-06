<?php namespace App\Service\Wechat\Hook;


use Abstracts\ReplyMessageInterface;
use App\Models\MemberModel;
use App\Service\Member\RegisteReward;
use App\Service\Users\MemberUser;
use App\Service\Wechat\Hook\Contracts\HookInterface;

class RegisterRewardHook  implements HookInterface {

    /**
     * @var ReplyMessageInterface
     */

    protected $message;

    public function hanlder(ReplyMessageInterface $message)
    {
        $this->message = $message;

        $registe_reward_service = new RegisteReward($this->member());

        $registe_reward_service->receive();
    }

    private function unionId()
    {
        return $this->message->getAttr('UnionId');
    }

    public function member()
    {
        $member_model = new MemberModel();

        $member_instance = MemberUser::getInstance();

        $member_instance->init($member_model->getByUnionId($this->unionId()));

        return $member_instance;
    }
}