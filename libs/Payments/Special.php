<?php

namespace Libs\Payments;

use App\Service\Row\MethodRow;
use App\Service\Row\OrderFromOrderNo;
use App\DataTypes\PayOrderStatus;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PaymentApplicationInterface;
use Libs\Payments\Helper\Exceptions\PayPaymentException;
use Libs\Payments\Helper\MakePaymentTrait;
use Libs\Payments\Special\Support\Config;

class Special implements PaymentApplicationInterface
{

    use MakePaymentTrait;

    private $payload;
    private $config;


    public function __construct(Collection $config)
    {
        $this->config = new Config($config);

        $this->payload = [
            'notify_url' => $config->get('notify_url', '')
        ];
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
        $result                   = $this->makePay(__CLASS__, $name, $this->config)->pay($this->payload, $params);
        $result['transaction_id'] = 'special_transid';

        return new Collection($result);
    }

    public function refund($name, Collection $order):Collection
    {
        $this->makePay(__CLASS__, $name, $this->config)->refund($order);

        $return = [
            "transaction_id" => 'special_transid_refund'
        ];

        return new Collection($return);
    }

    public function success()
    {
        return "success";
    }

    public function verify():Collection
    {
        $orignal  = \request()->all();
        $order_no = \request()->get('order_no');
        $params   = new Collection($orignal);
        $order    = new OrderFromOrderNo($order_no);
        $method   = new MethodRow($order->paymentId());

        $return = $this->makePay(__CLASS__, $method->tradeType(), $this->config)->verify($params);

        if ($return) {
            $params = $params->merge($return);
        }

        return $params;
    }

    public function callbackConversion($data):Collection
    {
        $data = new Collection($data);

        $status = $data->get('status');

        switch ($status) {
            case PayOrderStatus::PAY_STATUS_SUCCES:
            case PayOrderStatus::PAY_STATUS_FAIL:
            case PayOrderStatus::PAY_STATUS_CANCEL:
                break;
            default:
                throw new PayPaymentException('status错误:' . $status);
                break;
        }

        return new Collection([
            'status'     => $status,
            'status_msg' => $data->get('msg', ''),
            'amount'     => $data->get('amount')
        ]);
    }
}