<?php

namespace Libs\Payments;

use App\Exceptions\PayApiException;
use Libs\Log;
use Libs\Payments\Contracts\PaymentApplicationInterface;
use Libs\Payments\Helper\MakePaymentTrait;
use Libs\Payments\Upay\Support\Config;
use Libs\Payments\Upay\Support\Support;
use App\DataTypes\PayOrderStatus;
use Illuminate\Support\Collection;

class Upay implements PaymentApplicationInterface
{

    use MakePaymentTrait;

    const VERSION   = '1.4';
    const SIGN_TYPE = 'MD5';

    protected $config;

    protected $payload;

    public function __construct(Collection $config)
    {
        $terminalId   = $config->get('terminal_id', '');
        $merchant_id  = $config->get('merchant_id', '');
        $notify_url   = $config->get('notify_url', '');
        $this->config = new Config($config);

        $this->payload = [
            'merchant_id'     => $merchant_id,
            'notify_url'      => $notify_url,
            'version'         => self::VERSION,
            'terminal_id'     => $terminalId,
            'sign_type'       => self::SIGN_TYPE,
            'timestamp'       => date('Y-m-d H:i:s'),
            'request_id'      => Support::getRequestId($terminalId),
            'term_request_id' => Support::getTermRequestId($terminalId)
        ];
    }

    public function cancel($name, $order)
    {
        // TODO: Implement cancel() method.
    }

    public function close($name, $order)
    {
        // TODO: Implement close() method.
    }

    private function sendBizContent(&$params, $channel, $bizType, $bizContent)
    {
        $params['biz_channel'] = $channel;
        $params['biz_type']    = $bizType;
        $params['biz_content'] = json_encode($bizContent, JSON_UNESCAPED_UNICODE);
    }

    private function getBizContent(Collection $result)
    {
        $reBizContent = json_decode($result->get('biz_content'), true);
        return new Collection($reBizContent);
    }

    public function find($name, Collection $order):Collection
    {
        $orderCollect = new Collection($order);
        $params       = $this->payload;

        $bizContent = [
            "type"   => "common",
            'ext_no' => $orderCollect->get('order_no'),
        ];

        $this->sendBizContent($params, 'umszj.channel.common', 'umszj.trade.query', $bizContent);

        $params = Support::filterPayload($params, $this->config);
        $result = Support::requestApi('', $params, $this->config);

        return new Collection($result);
    }

    public function pay($name, Collection $params):Collection
    {
        return $this->makePay(__CLASS__, $name, $this->config)->pay($this->payload, $params);
    }

    public function refund($name, Collection $order):Collection
    {

        $pay          = $this->makePay(__CLASS__, $name, $this->config);
        $orderCollect = new Collection($order);
        $params       = $this->payload;

        $bizContent = [
            'ext_no'          => $orderCollect->get('order_no'),
            'trade_no'        => $orderCollect->get('transaction_id'),
            'refund_amount'   => $orderCollect->get('amount'),
            'refund_trade_no' => $orderCollect->get('refund_no'),
            'currency'        => 'CNY',
        ];

        $this->sendBizContent($params, $pay->getChannel(), 'umszj.trade.refund', $bizContent);

        $params       = Support::filterPayload($params, $this->config);
        $result       = Support::requestApi('', $params, $this->config);
        $reBizContent = $this->getBizContent($result);

        $return = [
            "transaction_id"    => $reBizContent->get('trade_no'),
            "openid"            => $reBizContent->get('buyer_id'),
            "attach"            => $reBizContent->get('attach'),
            "channel_refund_id" => $reBizContent->get('back_trade_no')
        ];

        return new Collection($return);
    }

    public function success()
    {
        return "success";
    }

    public function verify():Collection
    {
        $data = \request()->all();

        Log::debug('Receive Upay Notify', $data);

        if (!$data) {
            throw  new PayApiException("Upay notify error:not data", PayApiException::api_error);
        }

        return new Collection($data);
    }

    public function callbackConversion($data):Collection
    {
        $callData = new Collection($data);

        $resultData = [
            "amount"     => $callData->get("total_amount"),
            "pay_amount" => $callData->get("pay_amount"),
            "wx_appid"   => $callData->get("wx_appid", "")
        ];

        if ($callData->get('trade_no')) {
            $resultData['transaction_id'] = $callData->get('trade_no');
        }

        switch ($callData->get('trade_status')) {
            case "STARTUP":
                $resultData['status']     = PayOrderStatus::PAY_STATUS_PENDING;
                $resultData['status_msg'] = "待扫码";
                break;
            case "TRADE_SUCCESS":
                $resultData['status']     = PayOrderStatus::PAY_STATUS_SUCCES;
                $resultData['status_msg'] = "支付成功";
                break;
            case "TRADE_FAILED":
                $resultData['status'] = PayOrderStatus::PAY_STATUS_FAIL;
                break;
            case "TRADE_CLOSED":
                $resultData['status']     = PayOrderStatus::PAY_STATUS_CANCEL;
                $resultData['status_msg'] = "订单已关闭";
                break;
            case "TRADE_CANCELED":
                $resultData['status']     = PayOrderStatus::PAY_STATUS_REFUND;
                $resultData['status_msg'] = "取消";
                break;
            case "TRADE_WAITING_PAY":
                $resultData['status']     = PayOrderStatus::PAY_STATUS_PENDING;
                $resultData['status_msg'] = "用户未支付";
                break;
            default:
                $resultData['status']     = PayOrderStatus::PAY_STATUS_FAIL;
                $resultData['status_msg'] = "支付失败";
                break;
        }

        return new Collection($resultData);
    }
}