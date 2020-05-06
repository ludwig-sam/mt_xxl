<?php namespace App\Http\Controllers\Minipro;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Minipro\RechargeRule;
use App\Service\Recharge\RechargeService;
use App\Service\Row\RechargeRow;

class RechargeController extends BaseController
{

    public function rule()
    {
        return new RechargeRule();
    }

    private function service():RechargeService
    {
        return $this->newSingle(RechargeService::class);
    }

    public function recharge(ApiVerifyRequest $request)
    {
        $method_name          = $request->get('method');
        $id                   = $this->service()->createOrder($request, $method_name, $this->user());
        $ext_param            = $request->only(['card_no', 'card_pwd']);
        $ext_param['subject'] = '充值';

        $result = $this->service()->payment($id, $ext_param);

        return self::success('下单成功', $result);
    }

    public function get(ApiVerifyRequest $request)
    {
        $recharge = new RechargeRow($request->get('order_id'));

        return self::success('', $recharge->getRow());
    }
}