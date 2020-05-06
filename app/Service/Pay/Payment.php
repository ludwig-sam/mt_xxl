<?php namespace App\Service\Pay;

use Abstracts\Offsetable;
use App\Exceptions\PayPaymentException;
use App\Service\Row\MethodRow;
use App\Service\Row\MethodRowFromChannel;
use App\Service\Row\OrderRow;
use App\Models\PayMethodModel;
use App\Models\PayOrderModel;
use App\DataTypes\PayOrderStatus;
use App\PayConfig;
use App\Service\Mch\Mch;
use App\Service\Member\Member;
use App\Service\Service;
use Providers\Single\SingleAble;

class Payment extends Service
{
    use SingleAble;

    /**
     * @var MethodRow
     */
    private $payment;

    private function model():PayOrderModel
    {
        return $this->newSingle(PayOrderModel::class);
    }

    public function createOrder(Offsetable $offsetable)
    {
        $payOrderModel = $this->model();

        $this->fill($offsetable);

        $payOrderRow = $payOrderModel->insert($offsetable->all());

        if (!$payOrderRow->id) {
            throw new PayPaymentException("订单创建失败", PayPaymentException::create_order_fail);
        }

        $this->authCodeRelation($payOrderRow->auth_code, $payOrderRow->id);

        return $payOrderRow;
    }

    public function needAsync($payment_id)
    {
        $paymentMehtodModel = new PayMethodModel();
        return $paymentMehtodModel->isAsync($payment_id);
    }

    private function fill(Offsetable &$offsetable)
    {
        $offsetable->offsetSet('payment_id', $this->payment->id());
        $offsetable->offsetSet('payment_name', $this->payment->name());
        $offsetable->offsetSet('status', PayOrderStatus::PAY_STATUS_PENDING);
        $offsetable->offsetSet('amount', $this->calculationAmount($offsetable));

        $couponId   = intval($offsetable->offsetGet('coupon_id'));
        $couponCode = $offsetable->offsetGet('coupon_code');

        if ((bool)$couponId ^ (bool)$couponCode) {
            throw new PayPaymentException("miss coupon_id or coupon_code");
        }

        $offsetable->offsetSet('coupon_id', $couponId);
        $offsetable->offsetSet('coupon_code', $couponCode);

        $cardCode = $offsetable->offsetGet('card_code');

        if ($cardCode) {
            $memberService = new Member();
            $member        = $memberService->getMemberByCode($cardCode);
            $offsetable->offsetSet('member_id', $member->id);
            $offsetable->offsetSet('member_level', $member->level);
        }
    }

    private function hasChannel(Offsetable $request)
    {
        return $request->offsetExists('channel');
    }

    public function matchChannelByAuthCode($way, $authCode)
    {
        return PayConfig::matchByAuthCode($way, $authCode);
    }

    public function checkPay(Offsetable &$offsetable)
    {
        $mch_service = new Mch();
        $mchId       = $offsetable->offsetGet('mch_id');
        $mchPayWay   = $mch_service->getPaymentWay($mchId);
        $channel     = $offsetable->offsetGet('channel');
        $auth_code   = $offsetable->offsetGet('auth_code');

        Mch::ifStopThrow($mchId);

        if (!$this->hasChannel($offsetable)) {
            $channel = $this->matchChannelByAuthCode($mchPayWay, $auth_code);
            $offsetable->offsetSet('channel', $channel);
        }

        if ($auth_code && PayConfig::isSystemCode($auth_code)) {
            $paycode_service = new PayCode();
            $member_id       = $paycode_service->codeToMemberId($auth_code);
            $offsetable->offsetSet('member_id', $member_id);
        }

        $method = self::payMethodAndCheck($channel);
        $config = $mch_service->getPayConfig($mchId, $method->uCWay());

        if (!$config) {
            throw new PayPaymentException("商户没有配置此支付通道:" . $method->uCWay(), PayPaymentException::mch_payconfig_err);
        }

        $this->payment = $method;

        return $config;
    }

    private function authCodeRelation($auth_code, $order_id)
    {
        if (!$auth_code) return;

        $payment_code = new PayCode();

        $payment_code->codeOrderRelation($auth_code, $order_id);
    }

    public function calculationAmount(Offsetable $offsetable)
    {
        $discountService = new Discount();
        $auth_code       = $offsetable->offsetGet('auth_code');
        $coupon_id       = $offsetable->offsetGet('coupon_id');
        $coupon_code     = $offsetable->offsetGet('coupon_code');
        $card_id         = $offsetable->offsetGet('card_id');
        $card_code       = $offsetable->offsetGet('card_code');


        if (!$card_code && $auth_code && PayConfig::isSystemCode($auth_code)) {
            $pay_code_service = new PayCode();
            list($card_id, $card_code) = $pay_code_service->codeToCardAndCode($auth_code);
        }

        $offsetable->offsetSet('card_id', $card_id);
        $offsetable->offsetSet('card_code', $card_code);
        $discountService->pushCards($coupon_id, $coupon_code);
        $discountService->pushCards($card_id, $card_code);

        return $discountService->calculationAmount($offsetable->offsetGet('total_amount'), $offsetable);
    }

    public function pay($orderId, $configParam)
    {
        $order_row    = new OrderRow($orderId);
        $pay_service  = new PayService();
        $pay_method   = new MethodRow($order_row->paymentId());
        $order_data   = toCollection($order_row);
        $result       = $pay_service->pay($pay_method, $order_row, $configParam);
        $need_pay_pwd = $pay_service->isNeedPayPwd($pay_method, $order_row);
        $order_data   = $order_data->merge(compact('need_pay_pwd'));

        return $order_data->merge($result);
    }

    public function payFail($order_id, $msg)
    {
        $orderModle = new PayOrderModel();
        $order      = $orderModle->find($order_id);
        $order->edit([
            'status'     => PayOrderStatus::PAY_STATUS_FAIL,
            'status_msg' => $msg
        ]);
    }

    public static function mook()
    {
        return ((isDev() || isTest()) && isDebug() && request()->offsetGet('mook') === 'mook') ? 'mook_' : '';
    }

    public static function payMethodAndCheck($channel)
    {
        $method = new MethodRowFromChannel($channel);

        if ($method->isDisabled()) {
            throw  new PayPaymentException($method->name() . "被禁用", PayPaymentException::payment_mehtod_disable);
        }

        return $method;
    }

}

