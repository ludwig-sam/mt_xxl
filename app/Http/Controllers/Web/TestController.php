<?php

namespace App\Http\Controllers\Web;


use Libs\Log;
use Libs\Pay;
use Libs\Response;
use Libs\Route;
use App\Models\PayMethodModel;
use App\Models\PayOrderModel;
use App\Service\Mch\Mch;
use App\Service\Pay\Helpers\PayMethod;
use App\Service\Wechat\Auth;
use Illuminate\Http\Request;

class TestController
{

    public function rule()
    {
    }

    public function uploadView()
    {
        return view('upload.test');
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        $uploader = \Providers\UploadFactory::image($request->get('file'), 'file');

        echo($_FILES ? 'form' : 'string');
        echo "<br/>";

        if (!$uploader->up()) {
            dd($uploader->getError());
        }

        dd($uploader->getUploadedInfo());
    }

    public function route()
    {
        dd([
            'action' => Route::action()
        ]);
    }

    public function materialUpload()
    {
        return view('upload.material');
    }

    public function responseSend()
    {
        Response::success('', ['order_id' => 1, 'status' => 'pending'])->send();

        Log::warning(__FUNCTION__, [
            'order_id' => 11
        ]);
    }

    public function payFind()
    {
        $order_model      = new PayOrderModel();
        $pay_method_model = new PayMethodModel();
        $order_id         = 202;

        $order = $order_model->find($order_id);

        $mchService = new Mch();
        $mchPayWay  = $mchService->getPaymentWay($order->mch_id);

        $configParam = $mchService->getPayConfig($order->mch_id, $mchPayWay);

        $pay_method = $pay_method_model->find($order->payment_id);

        PayMethod::parseByPayment($pay_method);

        $result = Pay::payment(PayMethod::getWay(), $configParam)->find(PayMethod::getType(), $order->toArray());

        dd($result);
    }

    public function sessionFlush()
    {
        session()->flush();

        session()->save();

        dd(session()->all());
    }

    public function wxUserInfo(Request $request)
    {
        $auth = new Auth();

        return $auth->base($request->route()->uri, function ($user_info) {
            dd($user_info);
        });
    }

}
