<?php namespace App\Service\Wechat\Hook;


use App\Models\MemberModel;
use App\Models\PayOrderModel;
use App\Service\MemberLevel\MchMemberLevel;
use App\Service\Account\Account;
use App\Service\Users\MemberUser;
use Abstracts\ReplyMessageInterface;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;

class PaySuccessPointHook  extends PaySuccessExtendsAbstracts {

    public function name()
    {
        return 'point_execute';
    }

    public function do(ReplyMessageInterface $message)
    {
        $memberModel = new MemberModel();
        $orderModel  = new PayOrderModel();
        $orderId     = $message->getAttr('id');
        $orderRow    = $orderModel->find($orderId);
        $orderDetail = $orderRow->hasOneDetail;

        $memberId    = $message->getAttr('member_id');
        $memberDb    = $memberModel->find($memberId);

        MemberUser::getInstance()->init($memberDb);
        $memberUserInstance = MemberUser::getInstance();

        $mchMemberLvService = new MchMemberLevel($message->getAttr('mch_id'), $orderDetail->member_level);
        $point              = $mchMemberLvService->getUpdatePoint($message->getAttr('amount'));

        if(!$point){
            $this->success();
            return ;
        }

        $accountLog = new Account($memberUserInstance, Account::event_name_give, Account::scene_name_pay);

        $config = [
            'comment'   => '消费赠送',
            'order_id'  => $orderId,
            'mch_id'    => $message->getAttr('mch_id')
        ];

        $accountLog->pointAdd($point, $config);

        $orderRow->edit([
            'point' => $point
        ]);

        $this->success();
    }

}