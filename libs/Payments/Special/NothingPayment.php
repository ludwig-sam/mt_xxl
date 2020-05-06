<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午3:40
 */

namespace Libs\Payments\Special;


use App\Http\Codes\PayCode;
use Illuminate\Support\Collection;
use Libs\Log;
use Libs\Payments\Contracts\PayableInterface;
use Libs\Payments\Helper\Exceptions\PayPaymentException;
use Libs\Payments\Special\Support\Support;
use Libs\Unit;

class NothingPayment extends Pay implements PayableInterface
{

    public function getChannel()
    {
    }

    public function getTradeType()
    {
    }

    public function verify(Collection $params)
    {
        $sign        = $params->get('sign') ?: request()->headers->get('sign');
        $params      = $params->forget('sign');
        $expect_sign = Support::generateSign($params->toArray(), $this->config->getKey());
        $sign_string = Support::$sign_string;

        if ($expect_sign != $sign) {
            Log::error('签名错误', compact('expect_sign', 'sign', 'sign_string'));

            throw new PayPaymentException('签名错误', PayCode::pay_api_invalid_sign);
        }

        $total_amount = (int)request()->get('total_amount');
        $amount       = (int)request()->get('amount');

        return [
            'total_amount' => Unit::fentoYun($total_amount),
            'amount'       => Unit::fentoYun($amount),
        ];
    }

    public function pay(Array $payload, Collection $params):Collection
    {
        return new Collection();
    }

}