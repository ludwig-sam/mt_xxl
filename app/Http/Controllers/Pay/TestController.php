<?php namespace App\Http\Controllers\Pay;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Pay\PayRule;
use Libs\Arr;
use Libs\Response;
use Libs\Tree;
use App\Models\PayOrderModel;
use App\Models\PayRefundModel;
use App\Service\Pay\Payment;
use App\Service\Pay\Refund;
use Illuminate\Http\Request;
use Providers\RequestOffsetableAdapter;

class TestController extends BaseController {


    public function rule()
    {
    }

    public function filter(Request $request){

        return Response::success('', [
            'receive_amount' => $request->get('amount'),
            'return_amount'  => 0.02,
        ]);
    }

}