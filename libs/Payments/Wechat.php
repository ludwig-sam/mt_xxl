<?php

namespace Libs\Payments;

use App\DataTypes\PayOrderStatus;
use Libs\Payments\Contracts\PaymentApplicationInterface;
use Libs\Payments\Helper\Exceptions\PayApiException;
use Libs\Payments\Wechat\Support\Config;
use Libs\Payments\Wechat\Support\Support;
use EasyWeChat\Kernel\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Libs\Payments\Helper\MakePaymentTrait;
use Libs\Log;


class Wechat implements PaymentApplicationInterface
{
    use MakePaymentTrait;

    protected $config;

    protected $mode;

    protected $payload;

    public function __construct(Collection $config)
    {
        $this->config = new Config($config);

        $this->payload = [
            'appid'            => $this->config->getAppId(),
            'mch_id'           => $this->config->getMchId(),
            'nonce_str'        => Str::random(),
            'notify_url'       => $this->config->getNotifyUrl(),
            'spbill_create_ip' => Request::createFromGlobals()->getClientIp(),
        ];

        if ($this->config->getMode() === $this->config::MODE_SERVICE) {
            $this->payload = array_merge($this->payload, [
                'sub_mch_id' => $this->config->getSubMchId(),
                'sub_appid'  => $this->config->getSubAppId(),
            ]);
        }
    }

    public function cancel($name, $order)
    {
    }

    public function close($name, $order)
    {
    }

    public function find($name, Collection $order):Collection
    {
        return new Collection();
    }

    public function pay($name, Collection $params):Collection
    {
        $result = $this->makePay(__CLASS__, $name, $this->config)->pay($this->payload, $params);

        $result->offsetSet('transaction_id', $result->get('prepay_id'));

        return $result;
    }

    public function refund($name, Collection $order):Collection
    {
        $this->config->setSslVerify(true);

        $params                  = $this->payload;
        $params['total_fee']     = $order->get('amount');
        $params['refund_fee']    = $order->get('refund_amount');
        $params['out_trade_no']  = $order->get('order_no');
        $params['out_refund_no'] = $order->get('refund_no');

        $params['sign'] = Support::generateSign($params, $this->config->getKey());

        $result = Support::requestApi('secapi/pay/refund', $params, $this->config);

        return new Collection($result);
    }

    public function success()
    {
        return "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
    }

    public function verify():Collection
    {
        $request = Request::createFromGlobals();

        $data = Support::fromXml($request->getContent());

        Log::debug('Receive Wechat Request:', $data);

        if (Support::generateSign($data, $this->config->getKey()) !== $data['sign']) {

            throw new PayApiException('Wechat Sign Verify FAILED', PayApiException::pay_api_invalid_sign, $data);
        }

        return new Collection($data);
    }

    public function callbackConversion($data):Collection
    {
        $callData = new Collection($data);
        $status   = strtoupper($callData->get('result_code'));

        switch ($status) {
            case 'SUCCESS':
                $status = PayOrderStatus::PAY_STATUS_SUCCES;
                break;
            case 'FAIL':
                $status = PayOrderStatus::PAY_STATUS_FAIL;
                break;
            case 'CANCEL':
                $status = PayOrderStatus::PAY_STATUS_CANCEL;
                break;
            default:
                return new Collection([]);
        }

        $resultData = [
            'amount'         => $callData->get('total_fee'),
            'transaction_id' => $callData->get('transaction_id'),
            'status'         => $status
        ];

        return new Collection($resultData);
    }
}