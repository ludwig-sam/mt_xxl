<?php namespace App\Http\Controllers\Receive;


use App\Service\Pay\PayService;
use App\Service\Recharge\RechargeService;
use App\Service\Row\OrderFromOrderNo;
use App\Service\Row\RechargeRow;
use Libs\Log;


class PayNotifyController extends BaseController
{

    public function index($order_no)
    {
        try {
            request()->merge(['order_no' => $order_no]);
            
            $order       = new OrderFromOrderNo($order_no);
            $pay_service = new PayService();
            $response    = $pay_service->notify($order);

            return $response;

        } catch (\Exception $exception) {
            Log::error('pay_notify_exception' . $exception);

            return 'exception[' . $exception->getMessage() . ']';
        }
    }

    public function recharge($order_id)
    {

        try {

            $order       = new RechargeRow($order_id);
            $pay_service = new RechargeService();
            $response    = $pay_service->notify($order);

            return $response;

        } catch (\Exception $exception) {
            Log::error('recharge_notify_exception' . $exception);
        }

        return 'exception';
    }


}