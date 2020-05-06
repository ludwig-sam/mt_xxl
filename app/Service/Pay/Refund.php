<?php namespace App\Service\Pay;

use App\Exceptions\PayPaymentException;
use App\Http\Codes\Code;
use App\Jobs\ProcessRefundOrder;
use App\Service\Member\Member;
use App\Service\Row\OrderFromOrderNo;
use Illuminate\Support\Collection;
use Libs\Log;
use Libs\Pay;
use App\DataTypes\PayOrderStatus;
use App\Models\PayRefundModel;
use App\Service\Account\Account;
use App\Service\Service;
use App\Service\Users\CachierUser;
use App\Service\Users\MemberUser;

class Refund extends Service
{

    private $order;
    private $config;
    /**
     * @var \App\Service\Users\Contracts\UserAbstraict
     */
    private $user;

    public function __construct($order_no)
    {
        $this->order  = new OrderFromOrderNo($order_no);
        $this->config = new PaymentConfigFromOrder($this->order);

        $this->user();
    }

    private function user()
    {
        if ($this->user) return $this->user;

        if ($this->order->memberId()) {
            $member_service = new Member();
            $this->user     = $member_service->loginById($this->order->memberId());
        } else {
            $this->user = MemberUser::getInstance();
        }

        return $this->user;
    }

    public function refund($refundAmount)
    {

        $refundNo = Pay::orderNo();
        $config   = $this->config->config();
        $method   = $this->config->method();

        $this->check();

        $oldStatus = $this->order->status();

        $currentRefundAmount = $this->getCurrentRefundAmount($refundAmount);

        $param = new Collection(array_merge($this->order->toArray(), [
            'refund_no' => $refundNo,
            'amount'    => $currentRefundAmount
        ]));

        $result = Pay::payment($method->channel(), $config)->refund(Payment::mook() . $method->tradeType(), $param);

        $this->save($currentRefundAmount);

        $refundModel = new PayRefundModel();

        $data = array_merge($result->all(), [
            "order_id"      => $this->order->id(),
            "refund_no"     => $refundNo,
            "order_no"      => $this->order->orderNo(),
            "refund_amount" => $currentRefundAmount,
            "cashier_id"    => CachierUser::getInstance()->getId()
        ]);

        $refundModel->fill($data);

        if (!$refundModel->save()) {
            throw new PayPaymentException("网络错误");
        }

        if ($oldStatus == PayOrderStatus::PAY_STATUS_SUCCES && $this->order->memberId()) {
            $this->rebackMemberReward();
        }

        dispatch(new ProcessRefundOrder($this->order->getRow()));

        return [
            "order_no"      => $this->order->orderNo(),
            "refund_amount" => $currentRefundAmount,
            "refund_no"     => $refundNo
        ];
    }

    private function save($currentRefundAmount)
    {
        $refund_amount = $this->order->refundAmount();
        $refund_amount += $currentRefundAmount;

        $update_data = [
            'status'        => PayOrderStatus::PAY_STATUS_REFUND,
            'refund_amount' => $refund_amount
        ];

        if ($refund_amount == $this->order->amount()) {
            $update_data['status_msg'] = "全额退款";
        } else {
            $update_data['status_msg'] = "部分退款";
        }

        $this->order->getRow()->edit($update_data);
    }

    private function rebackMemberReward()
    {
        $user          = $this->user();
        $memberAccount = new Account($user, Account::event_name_reback, Account::scene_name_pay);

        try {
            $config = [
                "comment"  => "退款",
                "order_id" => $this->order->id(),
                'mch_id'   => $this->order->mchId()
            ];

            $this->order->point() && $memberAccount->pointReduce($this->order->point(), $config);

            $this->order->exp() && $memberAccount->expReduce($this->order->exp(), $config);

        } catch (\Exception $exception) {
            Log::error("退款会员更新失败", [
                'msg'  => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);
        }
    }

    public function check()
    {
        if (!$this->order) {
            throw new PayPaymentException("退款订单不存在:" . $this->order->orderNo(), PayPaymentException::order_not_exists);
        }

        if (!in_array($this->order->status(), [PayOrderStatus::PAY_STATUS_REFUND, PayOrderStatus::PAY_STATUS_SUCCES])) {
            throw new PayPaymentException("订单未支付或已关闭", PayPaymentException::refund_status_err);
        }

        if ($this->order->amount() && $this->order->refundAmount() == $this->order->amount()) {
            throw new PayPaymentException("订单已全额退款", PayPaymentException::refund_success);
        }

        if ($this->order->memberId()) {
            $this->checkRebackReward();
        }
    }

    private function checkRebackReward()
    {
        $point = $this->user->getAttribute('point');
        $exp   = $this->user->getAttribute('exp');

        if ($point < $this->order->point()) {
            throw new PayPaymentException("会员积分不足", Code::fail);
        }

        if ($exp < $this->order->exp()) {
            throw new PayPaymentException("会员经验不足", Code::fail);
        }
    }

    private function getCurrentRefundAmount($refundAmount)
    {
        if (!$refundAmount) {
            $refundAmount = $this->order->amount() - $this->order->refundAmount();
        }

        if ($this->order->refundAmount() + $refundAmount > $this->order->amount()) {
            throw new PayPaymentException("退款金额大于订单总金额", PayPaymentException::refund_fail);
        }

        return $refundAmount;
    }

}

