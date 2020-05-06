<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/28
 * Time: 下午4:27
 */

namespace App\Service\Hooks;


use App\DataTypes\RecharegeStatus;
use App\Service\Account\Account;
use App\Service\Member\Member;
use Providers\Hook\Contracts\HookInterface;
use Providers\Hook\Contracts\HookMessageContract;

class RechargeSuccessHook implements HookInterface
{
    /**
     * @var HookMessageContract
     */
    private $message;

    public function handle(HookMessageContract $message)
    {
        $this->message = $message;

        $this->toAccount();
    }

    private function memberId()
    {
        return $this->message->member_id;
    }

    private function isSuccess()
    {
        return $this->message->status == RecharegeStatus::success;
    }

    private function orderId()
    {
        return $this->message->id;
    }

    private function amount()
    {
        return $this->message->amount;
    }

    private function toAccount()
    {
        if (!$this->isSuccess()) return;

        $login   = new Member();
        $user    = $login->loginById($this->memberId());
        $account = new Account($user, Account::event_name_give, Account::scene_name_recharge);

        $account->balanceAdd($this->amount(), [
            'order_id' => $this->orderId(),
            'comment'  => '充值'
        ]);
    }
}