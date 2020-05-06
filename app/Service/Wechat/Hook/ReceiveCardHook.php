<?php namespace App\Service\Wechat\Hook;



use App\Exceptions\ReplyException;
use App\Models\CardCodeModel;
use App\DataTypes\CardTypes;
use App\Models\MemberModel;
use App\Service\Card\States\CardActor;
use Abstracts\ReplyMessageInterface;
use App\Service\Member\Member;
use App\Service\Users\Contracts\UserAbstraict;
use App\Service\Users\MemberUser;
use App\Service\Wechat\Hook\Contracts\HookInterface;

class ReceiveCardHook  implements HookInterface {

    /**
     * @var ReplyMessageInterface
     */
    private $message;

    public function hanlder(ReplyMessageInterface $message)
    {
        $this->message = $message;

        $cardActor = new CardActor($this->wxCardId());

        //必须

        $this->init();

        $member = $this->member();

        if(! $cardActor->receive($this->code())){
            throw new ReplyException($cardActor->result()->getMsg());
        }

        $this->memberCard($member, $cardActor->getCardInfo());
    }

    private function init()
    {
        $this->member();
    }

    private function member()
    {
        $memberModel = new MemberModel();
        $memberInfo = $memberModel->getByUnionId($this->unionid());

        if(! $memberInfo)throw new ReplyException("用户不存在");

        $member_instance = MemberUser::getInstance();
        $member_instance->init($memberInfo);

        return $member_instance;
    }

    private function code(){
        return  $this->message->getAttr('UserCardCode');
    }

    private function wxCardId()
    {
        return  $this->message->getAttr('CardId');
    }

    private function unionid()
    {
        return $this->message->getAttr('UnionId');
    }

    private function memberCard(UserAbstraict $user, $card_info)
    {
        $cardCodeModel  = new CardCodeModel();

        $code_row = $cardCodeModel->where('member_id', $user->getId())->where('code_no', $this->code())->first();

        if(!$code_row)return ;

        if($card_info['type'] == CardTypes::member_card){
            $member_service = new Member();
            $member_service->saveMemberCode($user->getId(), $this->code(), $code_row->id);
        }
    }
}