<?php namespace App\Http\Controllers\Mchs;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\PayConfigRule;
use Libs\Response;
use App\Service\Mch\Mch;
use App\Service\Pay\PayWayFactory;
use Illuminate\Support\Collection;

class PayConfigController extends BaseController {


    public function rule()
    {
        return new PayConfigRule();
    }

    public function update(ApiVerifyRequest $request){
        $reqCollection = new Collection($request);
        $mchId         = $this->user()->getMchId();
        $way           = $reqCollection->get('way');

        $payWayInstance = PayWayFactory::make($way);
        $way            = $payWayInstance->way();

        $configParam = $payWayInstance->param($reqCollection);
        $mchService  = new Mch();

        if(!$mchService->savePayConfig($mchId, $way, $configParam)){
            return Response::error(Code::update_fail, '设置失败');
        }
	    $detial = "更新了upay:".$way."的支付配置";
	    self::note("更新支付配置",$detial);
	    return Response::success('设置成功');
    }

    public function get()
    {
        $mchService = new Mch();
        $mchId      = $this->user()->getMchId();
        $list       = $mchService->getCurPayWays($mchId);
        $mchInfo    = $mchService->getInfo($mchId);

        return Response::success('', [
            'using'  => $mchInfo['payment_way'],
            'list'   => $list,
        ]);
    }
}