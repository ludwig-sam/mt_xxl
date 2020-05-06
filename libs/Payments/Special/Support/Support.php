<?php

namespace Libs\Payments\Special\Support;


use App\DataTypes\RechargeTypes;
use App\Service\Row\RechargeRow;
use Libs\Log;
use App\Models\MchModel;
use App\Models\PayOrderModel;
use App\DataTypes\PayOrderStatus;
use Illuminate\Support\Collection;
use Libs\Payments\Helper\Exceptions\PayPaymentException;
use Libs\Time;
use Providers\HasRequestTrait;

class Support
{

    use HasRequestTrait;

    private static $instance;
    private        $baseUrl;

    static $sign_string;


    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public static function getInstance($baseUrl)
    {
        if (!(self::$instance instanceof Support)) {
            self::$instance = new Support($baseUrl);
        }

        return self::$instance;
    }

    public function getBaseUri()
    {
        return $this->baseUrl;
    }

    public static function sendPayNotify($notify_url, Collection $order)
    {
        Log::info("send special pay notify");

        $order = $order->merge([
            'status' => PayOrderStatus::PAY_STATUS_SUCCES,
            'msg'    => '支付成功',
        ]);

        return self::getInstance($notify_url)->post('', $order->all());
    }

    public static function paySuccess($order_id, $ext_data = [])
    {
        $order_model = new PayOrderModel();
        $mch_model   = new MchModel();

        $order_row = $order_model->find($order_id);
        $mch_id    = $order_row->mch_id;
        $update    = [
            'status'     => PayOrderStatus::PAY_STATUS_SUCCES,
            'status_msg' => '支付成功',
            'payment_at' => Time::date()
        ];

        $ret = $order_row->edit(array_merge($update, $ext_data));

        $mch_id && $mch_model->incrementTranscationNumber($mch_id);

        return $ret;
    }

    public static function generateSign($data, $key = null):string
    {
        if (is_null($key)) {
            throw new PayPaymentException('Missing Wechat Config -- [key]', PayPaymentException::miss_key);
        }

        self::$sign_string = self::getSignContent($data);

        $string = md5(self::$sign_string . '&key=' . $key);

        return strtoupper($string);
    }

    public static function getSignContent($data):string
    {
        ksort($data);

        $buff = [];

        foreach ($data as $k => $v) {

            if (self::signFilter($v)) continue;

            $v = self::signFormat($v);

            $buff[] = $k . '=' . $v;
        }

        return join('&', $buff);
    }

    private static function signFilter($v)
    {
        return ($v == '' || is_array($v));
    }

    private static function signFormat($v)
    {
        if (is_bool($v)) return $v == true ? 'true' : 'false';

        return $v;
    }

    public static function rechargeSuccess($order_id, $ext_data = [])
    {
        $recharge = new RechargeRow($order_id);
        $update   = [
            'status'     => RechargeTypes::status_success,
            'payment_at' => Time::date()
        ];

        return $recharge->getRow()->update(array_merge($update, $ext_data));

    }

}