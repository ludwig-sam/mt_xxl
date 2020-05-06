<?php namespace App\Service\Member;


use Libs\Time;
use App\DataTypes\MessagePlaceholders;
use App\DataTypes\MessageSendRoots;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Users\MemberUser;
use Illuminate\Database\Eloquent\Model;

class MemberIsNotMem implements Actable, MessageProviderInterface
{

    private $actor;
    /**
     * @var Model
     */
    private $member;

    public function __construct(MemberActor &$memberActor)
    {
        $this->actor = $memberActor;
    }

    public function getMessageParam()
    {
        $info = $this->member->toArray();

        $info[MessagePlaceholders::become_member_notify_registe_at] = Time::date();

        return $info;
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::become_member_notify;
    }

    public function getMessageTo()
    {
        if(!$this->member->openid){
            return [];
        }

        return [$this->member->openid];
    }

    public function becomeMember($memberData)
    {
        $model = MemberUser::getInstance()->model();

        $model->is_member = 1;
        $model->fill($memberData);

        if(!$model->save()){
            $this->actor->setError("会员激活失败");

            return false;
        }

        $this->member = $model;

        MessageTirgger::instance()->trigger($this);

        return $model->id;
    }

}