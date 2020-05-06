<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/28
 * Time: 下午2:40
 */

namespace App\Service\Recharge;


use App\DataTypes\RecharegeStatus;
use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Exceptions\PayPaymentException;
use App\Http\Codes\PayCode;
use App\Models\RechargeModel;
use App\Service\Row\MethodRow;
use App\Service\Row\MethodRowFromChannel;
use App\Service\Row\RechargeRow;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;
use Libs\Pay;
use Libs\Route;
use Libs\Time;
use Providers\Curd\CurdServiceTrait;

class RechargeService extends Service
{

    private $order_expire = 600;

    use CurdServiceTrait;

    public function model():RechargeModel
    {
        return $this->newSingle(RechargeModel::class);
    }

    private function orderNo()
    {
        return Pay::orderNo();
    }

    public function createOrder($request, $method_key, UserAbstraict $user)
    {
        $method = new MethodRowFromChannel($method_key);
        $data   = new Collection([
            'payment_id'   => $method->id(),
            'payment_name' => $method->name(),
            'order_no'     => self::orderNo(),
            'member_id'    => $user->getId(),
            'openid'       => $user->getAttribute('mini_openid'),
            'expired_at'   => Time::date(\time() + $this->order_expire)
        ]);
        $data   = $data->merge($request);

        $ret = $this->create($data);

        if (!$ret) {
            throw new ExceptionCustomCodeAble('订单创建失败');
        }

        return $ret->id;
    }

    public function payment($id, $ext_param)
    {
        $order_row            = new RechargeRow($id);
        $order_data           = toCollection($order_row);
        $pay_method           = new MethodRow($order_row->paymentId());
        $config               = $pay_method->config();
        $config['notify_url'] = Route::named('recharge_notify', $order_row->id());
        $params               = toCollection($order_row);
        $params               = $params->merge($ext_param);

        $result = Pay::payment($pay_method->channel(), $config)->pay($pay_method->tradeType(), $params);
        $update = [
            'trade_no' => $result->get('transaction_id'),
        ];

        $this->updatePrepay($order_row, $update);

        return $order_data->merge($result);
    }

    private function updatePrepay(RechargeRow $order, $data)
    {
        $data = toArray($data);
        $ret  = $order->getRow()->update($data);

        if (!$ret) {
            throw new ExceptionCustomCodeAble('订单更新失败');
        }

        return $ret;
    }

    private function paymentNotify(RechargeRow $order, $data)
    {
        $data               = toArray($data);
        $data['payment_at'] = Time::date();
        return $order->getRow()->update($data);
    }

    private function event($order)
    {
        systemEvent($order, 'recharge');
    }

    public function notify(RechargeRow $order)
    {
        $method   = new MethodRow($order->paymentId());
        $instance = Pay::payment($method->channel(), $method->config());
        $new_data = $instance->callbackConversion($instance->verify());

        if ($order->isSuccess()) {
            return $instance->success();
        }

        if ($new_data->get('amount') != $order->amount()) {
            $msg = "money error " . join('!=', [$new_data->get('amount'), $order->amount()]);

            throw new PayPaymentException($msg, PayCode::invalid_money);
        }

        $this->paymentNotify($order, $new_data);

        $this->event(new RechargeRow($order->id()));

        return $instance->success();
    }

    public function rechargeLimit($limit, $request)
    {
        $request = new Collection($request);

        return $this->model()->manageLimit($limit, $request);
    }

    public function total($request)
    {
        $request = new Collection($request);
        $request = $request->merge([
            'status' => RecharegeStatus::success
        ]);

        return $this->model()->total($request);
    }
}