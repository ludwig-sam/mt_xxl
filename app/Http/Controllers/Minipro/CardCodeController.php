<?php namespace App\Http\Controllers\Minipro;


use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Minipro\CodeRule;
use Libs\Response;
use App\Models\CardCodeModel;
use App\Service\Card\CodeService;
use Illuminate\Support\Collection;

class CardCodeController extends BaseController
{


    public function rule()
    {
        return new CodeRule();
    }

    public function lists(ApiVerifyRequest $request)
    {
        $card_code_model = new CardCodeModel();
        $status = $request->get('status');

        if($status == 'INVALID'){
            $list = $card_code_model->myInvalidCardsLimit($this->limitNum(), new Collection($request));
        }else{
            $list = $card_code_model->myCardsLimit($this->limitNum(), new Collection($request));
        }

        return Response::success('', $list);
    }

    public function isScan(ApiVerifyRequest $request)
    {
        $code = $request->get('code');

        $code_service = new CodeService();

        return Response::success('', ['is_scan' => $code_service->isScan($code)]);
    }

    public function scanComplete(ApiVerifyRequest $request)
    {
        $code = $request->get('code');

        $code_service = new CodeService();

        if(!$code_service->scanComplete($code)){
            return Response::error(Code::fail, '取消扫码状态失败');
        }

        return Response::success('');
    }

}