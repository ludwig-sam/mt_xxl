<?php namespace App\Service\Wechat\Hook;


use Abstracts\ReplyMessageInterface;
use App\Models\MemberlevelModel;
use App\Models\MemberModel;
use App\DataTypes\MessageSendRoots;
use App\Models\PayOrderDetailModel;
use App\Service\Account\Account;
use App\Service\Member\Member;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Users\MemberUser;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;


class PaySuccessMemberLevelHook extends PaySuccessExtendsAbstracts implements MessageProviderInterface
{

    private $members = [];

    private $message_param = [];

    public function getMessageParam()
    {
        $member = $this->getMember();

        $param = [];

        if($member){
            $param = $member->toArray();
        }

        return array_merge($param, $this->message_param);
    }

    public function getMessageTo()
    {
        $member_row = $this->getMember();

        if($member_row && $member_row->openid){
            return [$member_row->openid];
        }

        return [];
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::member_exp_notify;
    }

    public function name()
    {
        return 'member_level_execute';
    }

    private function getMember()
    {
        if(!isset($this->members[$this->memberId()])){

            $member = new Member();
            $member_row = $member->getMemberAndCheck($this->memberId());

            $this->members[$this->memberId()] = $member_row;
        }

        return $this->members[$this->memberId()];
    }

    private function orderId()
    {
        return $this->message->id;
    }

    private function mchId()
    {
        return $this->message->mch_id;
    }

    private function getOrderDetailRow()
    {
        $order_detail_model = new PayOrderDetailModel();

        $order_detail_row = $order_detail_model->where('order_id', $this->orderId())->first();

        return $order_detail_row;
    }

    private function memberId()
    {
        return $this->message->member_id;
    }

    private function getAddExp($member_row, $level_row)
    {
        return $level_row->exp - $member_row->old_exp;
    }

    private function isSatisfy($level_row)
    {
        return $level_row->consume >= $this->message->getAttr('amount');
    }

    private function getUser()
    {
        $member_model = new MemberModel();

        $member_row = $member_model->find($this->memberId());

        MemberUser::getInstance()->init($member_row);

        return MemberUser::getInstance();
    }

    private function finalAddExp($member_row)
    {
        $member_level_model = new MemberlevelModel();

        $level_rows = $member_level_model->get();

        foreach($level_rows as $level_row){
            if($this->isSatisfy($level_row)){
                return $this->getAddExp($member_row, $level_row);
            }
        }

        return 0;
    }

    private function toAccount($add_exp)
    {
        $user = $this->getUser();

        $account_log = new Account($user, Account::event_name_give, Account::scene_name_pay);

        $account_log->expAdd($add_exp, ['comment' => '消费赠送', 'order_id' => $this->orderId(), 'mch_id' => $this->mchId()]);

        $this->getOrderDetailRow()->update(['exp' => $add_exp]);
    }

    public function do(ReplyMessageInterface $message)
    {
        if(!$this->memberId()){
            return;
        }

        $member_row = $this->getMember();

        $add_exp = $this->finalAddExp($member_row);

        if(!$add_exp) return;

        $this->toAccount($add_exp);

        $this->success();
    }


}