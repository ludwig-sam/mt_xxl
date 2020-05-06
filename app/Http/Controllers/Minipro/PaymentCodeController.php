<?php namespace App\Http\Controllers\Minipro;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Minipro\PaymentCodeRule;
use App\Service\Pay\PayCode;
use App\Service\Pay\PayCodeBalance;
use App\Service\Pay\PayService;
use App\Service\Row\MethodRow;
use App\Service\Row\OrderRow;
use Illuminate\Support\Collection;
use Libs\Route;

class PaymentCodeController extends BaseController
{

    public function rule()
    {
        return new PaymentCodeRule();
    }

    public function balanceCode()
    {
        $balance_code = new PayCodeBalance();
        $code         = $balance_code->generate($this->user());
        $qrcode_url   = Route::named('payment_qrcode', compact('code'));
        $barcode_url  = Route::named('payment_barcode', compact('code'));

        return self::success('', compact('code', 'qrcode_url', 'barcode_url'));
    }

    public function order(ApiVerifyRequest $request)
    {
        $pay_code             = new PayCode();
        $code                 = $request->get('code');
        $order_id             = $pay_code->getOrderIdByCode($code);
        $order_row            = new OrderRow($order_id);
        $data                 = toArray($order_row);
        $pay_service          = new PayService();
        $method               = new MethodRow($order_row->paymentId());
        $data['need_pay_pwd'] = $pay_service->isNeedPayPwd($method, $order_row);

        if ($order_row->isSuccess()) {
            $pay_code = new PayCode();
            $pay_code->deleteRalation($code);
        }

        return self::success('', $data);
    }

    public function surePayment(ApiVerifyRequest $request)
    {
        $pay_service = new PayService();
        $order_no    = $request->get('order_no');

        $pay_service->specialSurePay($order_no, self::user(), new Collection($request));

        return self::success('完成');
    }
}