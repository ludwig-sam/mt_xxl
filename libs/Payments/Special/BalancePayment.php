<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午3:40
 */

namespace Libs\Payments\Special;


use App\Service\Account\Account;
use App\Service\Member\Member;
use App\Service\Row\OrderFromOrderNo;
use App\Service\Row\OrderRow;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;

class BalancePayment extends Pay implements PayableInterface
{
    public function getChannel()
    {
    }

    public function getTradeType()
    {
    }

    public function refund(Collection $order)
    {
        $order_no = $order->get('order_no');
        $order    = new OrderFromOrderNo($order_no);
        $member_s = new Member();
        $user     = $member_s->loginById($order->memberId());
        $account  = new Account($user, Account::event_name_reback, Account::scene_name_pay);
        $account->balanceAdd($order->amount(), [
            'order_id' => $order->id(),
            'comment'  => '退款退余额'
        ]);
    }

    private function check(OrderRow $order)
    {
        $member_service = new Member();
        $member_id      = $order->memberId();
        $user           = $member_service->loginById($member_id);
        $member_service->checkBalance($user, $order->amount());
    }

    public function verify(Collection $params)
    {
        $order_no = $params->get('order_no');
        $order    = new OrderFromOrderNo($order_no);
        $this->check($order);
    }

    public function pay(Array $payload, Collection $params):Collection
    {
        $order_no = $params->get('order_no');
        $order    = new OrderFromOrderNo($order_no);
        $this->check($order);

        return new Collection();
    }

}