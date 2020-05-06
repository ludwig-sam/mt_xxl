<?php namespace App\Http\Controllers\Pay;


use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Pay\PayRule;
use Libs\Log;
use Libs\Response;
use Libs\Unit;
use App\Models\PayOrderModel;
use App\Models\PayRefundModel;
use App\Service\Pay\Payment;
use App\Service\Pay\Refund;
use Illuminate\Support\Collection;
use Providers\RequestOffsetableAdapter;

class OrderController extends BaseController
{


    public function rule()
    {
        return new PayRule();
    }

    public function create(ApiVerifyRequest $request)
    {
        $service = new Payment();

        $request->offsetSet('mch_id', $this->user()->getMchId());
        $request->offsetSet('store_id', $this->user()->getAttribute('store_id'));
        $request->offsetSet('cashier_id', $this->user()->getId());
        $request->offsetSet('exe_id', $this->user()->getAttribute('exe_id'));

        $request_offset = new RequestOffsetableAdapter($request);

        $payConfigParam = $service->checkPay($request_offset);
        $order_row      = $service->createOrder($request_offset);

        if ($service->needAsync($order_row->payment_id)) {
            return $this->sendNow($service, $order_row, $payConfigParam);
        }

        return $this->sendAfterPay($service, $order_row, $payConfigParam);
    }

    private function sendAfterPay(Payment $service, $order_row, $mchPaymentConfig)
    {
        $order_info = $service->pay($order_row->id, $mchPaymentConfig);

        return Response::success('', $order_info);
    }

    private function sendNow(Payment $service, $order_row, $payConfigParam)
    {
        $order_row->amount       = Unit::yuntoFen($order_row->amount);
        $order_row->total_amount = Unit::yuntoFen($order_row->total_amount);

        Response::success('', $order_row)->send();

        try {
            $service->pay($order_row->id, $payConfigParam);

        } catch (\Exception $exception) {

            Log::error("支付报错", [
                'msg'  => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ]);

            $service->payFail($order_row->id, $exception->getMessage());
        }
    }

    public function refund(ApiVerifyRequest $request)
    {
        $service = new Refund($request->get('order_no'));
        $data    = $service->refund($request->get("refund_amount"));

        return Response::success('', $data);
    }

    public function payList(ApiVerifyRequest $request)
    {
        $payOrderModel = new PayOrderModel();

        $request->offsetSet('mch_id', $this->user()->getMchId());
        $request->offsetSet('store_id', $request->get('store_id', $this->user()->getAttribute('store_id')));

        $list = $payOrderModel->payList(new Collection($request->all()), $this->limitNum());

        return Response::success('', $list);
    }


    public function refundList(ApiVerifyRequest $request)
    {
        $refundModel = new PayRefundModel();

        $request->offsetSet('mch_id', $this->user()->getMchId());
        $request->offsetSet('store_id', $request->get('store_id', $this->user()->getAttribute('store_id')));


        $list = $refundModel->refundList(new Collection($request->all()), $this->limitNum());

        return Response::success('', $list);
    }

    public function orderQuery(ApiVerifyRequest $request)
    {
        $payOrderModel = new PayOrderModel();
        if (!$data = $payOrderModel->orderQuery($request->all())) {
            return Response::error(WeiCode::get_payOrder_fail, '支付订单不存在');
        }
        return Response::success('', $data);
    }

    public function refundQuery(ApiVerifyRequest $request)
    {
        $refundModel = new PayRefundModel();
        if (!$data = $refundModel->refundQuery($request->all())) {
            return Response::error(WeiCode::get_refundOrder_fail, '退款订单不存在');
        }
        return Response::success('', $data);
    }
}