<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/25
 * Time: 下午2:17
 */

namespace App\Service\Pay;


use App\Http\Codes\Code;
use App\Http\Codes\PayCode;
use App\Service\Member\MemberAccountConfigService;
use App\Service\Row\OrderFromOrderNo;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;
use Libs\Arr;
use Libs\Log;
use Libs\Pay;
use Libs\Payments\Special\Support\Support;
use Libs\Route;
use Libs\Payments\Helper\Exceptions\PayPaymentException;
use App\Service\Row\MethodRow;
use App\Service\Row\OrderRow;
use Libs\Time;
use App\Models\PayOrderExtendsFunLogModel;
use App\Jobs\ProcessPayFailOrder;
use App\Jobs\ProcessPaySuccessOrder;

class PayService
{

    public function updatePrepay(OrderRow $order, $data)
    {
        $data = toArray($data);

        return $order->getRow()->update($data);
    }

    public function paymentNotify(OrderRow $order, $data)
    {
        $data               = toArray($data);
        $data['payment_at'] = Time::date();
        return $order->getRow()->update($data);
    }

    public function pay(MethodRow $methodRow, OrderRow $order, $config)
    {
        $order_info           = toArray($order);
        $complate_info        = Arr::reduceMerge($order_info);
        $params               = new Collection($complate_info);
        $config['notify_url'] = Route::named('pay_notify', $order->orderNo());
        $result               = Pay::payment($methodRow->channel(), $config)->pay($methodRow->tradeType(), $params);

        $update = [
            'transaction_id' => $result->get('transaction_id'),
            'openid'         => $result->get('openid')
        ];

        $this->updatePrepay($order, $update);

        return $result;
    }

    public function paymentInstance(OrderRow $order)
    {
        $config_row = new PaymentConfigFromOrder($order);
        $config     = $config_row->config();
        $method     = $config_row->method();
        return Pay::payment($method->channel(), $config);
    }

    public function notify(OrderRow $order)
    {
        $config_row = new PaymentConfigFromOrder($order);
        $config     = $config_row->config();
        $method     = $config_row->method();
        $instance   = Pay::payment($method->channel(), $config);
        $new_data   = $instance->callbackConversion($instance->verify());

        if ($order->isSuccess()) {
            return $instance->success();
        }

        if ($new_data->get('amount') != $order->amount()) {
            throw new PayPaymentException("money error", PayCode::invalid_money);
        }

        $this->paymentNotify($order, $new_data);

        $this->eventPay($order);

        return $instance->success();
    }

    private function eventPay(OrderRow $order)
    {
        $order = new OrderRow($order->id());

        if ($order->isSuccess()) {
            $this->saveExtendsLog($order);
            dispatch(new ProcessPaySuccessOrder($order));
        } else {
            dispatch(new ProcessPayFailOrder($order));
        }
    }

    private function saveExtendsLog(OrderRow $order)
    {
        $payOrderExtendsModle = new PayOrderExtendsFunLogModel();

        if ($payOrderExtendsModle->getByOrderId($order->id())) {
            return;
        }

        $payOrderExtendsModle->createNew($order->id());
    }

    public function refundNotify(MethodRow $methodRow, $config)
    {
        $instance = Pay::payment($methodRow->channel(), $config);

        return $instance->success();
    }

    public function isNeedPayPwd(MethodRow $method, OrderRow $order)
    {
        return (bool)$method->isNeedPwd();
    }

    public function checkPayPwd(UserAbstraict $user, $pwd)
    {
        $member_config = new MemberAccountConfigService($user);

        if ($member_config->getPayPwd() != md5($pwd)) {
            throw new \App\Exceptions\PayPaymentException('支付密码错误', 'password_error', [
                'user_id'   => $user->getId(),
                'input_pwd' => $pwd,
                'pay_pwd'   => $member_config->getPayPwd()
            ]);
        }

        return true;
    }

    public function specialSurePay($order_no, UserAbstraict $user, Collection $request)
    {
        $pay_pwd = $request->get('pay_pwd');
        $order   = new OrderFromOrderNo($order_no);
        $method  = new MethodRow($order->paymentId());
        $payment = Pay::payment($method->channel(), []);

        if ($order->isSuccess()) {
            return true;
        }

        if (!$order->isPeding()) {
            throw new \App\Exceptions\PayPaymentException('状态错误', Code::create_fial);
        }

        if (!$method->isBalance()) {
            throw new \App\Exceptions\PayPaymentException('非法入口', Code::create_fial);
        }

        if ($this->isNeedPayPwd($method, $order)) {
            $this->checkPayPwd($user, $pay_pwd);
        }

        $response = Support::sendPayNotify(Route::named('pay_notify', $order->orderNo()), toCollection($order));

        if ($payment->success() != $response) {
            throw new \App\Exceptions\PayPaymentException('支付失败');
        }

        return true;
    }
}