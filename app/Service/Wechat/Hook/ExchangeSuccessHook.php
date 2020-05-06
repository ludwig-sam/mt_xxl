<?php namespace App\Service\Wechat\Hook;



use Abstracts\ReplyMessageInterface;
use App\Exceptions\MemberException;
use App\Http\Codes\Code;
use Libs\Str;
use App\Models\MemberModel;
use App\Service\Activity\Exchange;
use App\Service\Users\MemberUser;
use App\Service\Wechat\Hook\Contracts\HookInterface;

class ExchangeSuccessHook  implements HookInterface {

    /**
     * @var ReplyMessageInterface
     */
    private $message;


    public function hanlder(ReplyMessageInterface $message)
    {
        $this->message = $message;

        $member = $this->member();

        $member_instance = MemberUser::getInstance();

        $member_instance->init($member);

        $exchange_service = new Exchange();

        $exchange_service->consumeSuccess($this->getActivatyId(), $member_instance);

    }

    private function getActivatyId()
    {
        return Str::last($this->getOriginOutStr(), ':');
    }

    private function getOriginOutStr()
    {
        return $this->message->getAttr('OuterStr');
    }

    private function unionid()
    {
        return $this->message->getAttr('UnionId');
    }

    private function member()
    {
        $member_model  = new MemberModel();

        $member_row = $member_model->getByUnionId($this->unionid());

        if(!$member_row){
            throw new MemberException("会员不存在:", Code::not_exists, $this->message->toArray());
        }

        return $member_row;
    }

}