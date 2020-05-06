<?php namespace App\Service\Wechat\Hook;


use Abstracts\ReplyMessageInterface;
use App\PayConfig;
use App\Service\Account\Account;
use App\Service\Member\Member;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;

class PaySuccessConsumeBalanceHook extends PaySuccessExtendsAbstracts
{

    public function name()
    {
        return 'pay_success_balance';
    }

    public function do(ReplyMessageInterface $message)
    {
        if ($this->paymentId() != PayConfig::PAYMENT_BALANCE) return;

        $member_service = new Member();
        $user           = $member_service->loginById($this->memberId());

        try {
            $account = new Account($user, Account::event_name_consume, Account::scene_name_pay);

            $account->balanceReduce($this->amount(), [
                'comment'  => '余额支付',
                'order_id' => $this->orderId()
            ]);

            $this->success();

        } catch (\Exception $exception) {
            $this->throw($exception->getMessage());
        }
    }

    function memberId()
    {
        return $this->message->getAttr('member_id');
    }

    function paymentId()
    {
        return $this->message->getAttr('payment_id');
    }

    function orderId()
    {
        return $this->message->getAttr('id');
    }

    function amount()
    {
        return $this->message->getAttr('amount');
    }

}