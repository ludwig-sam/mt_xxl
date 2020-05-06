<?php namespace App\Http\Controllers\Pay;


use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Pay\PayRule;
use Libs\Response;
use App\Models\ExeModel;
use App\DataTypes\ExeStatus;
use App\Service\Cashier\Cashier;
use App\Service\Mch\Mch;
use App\Service\Token\AccessToken;

class CashierController extends BaseController {

    protected $needAccessToken = false;

    public function rule()
    {
        return new PayRule;
    }

    public function login(ApiVerifyRequest $request){

        $userName = $request->get('username');
        $password = $request->get('password');
        $devNo    = $request->get('dev_no');
        $cacheierService = new Cashier();
        $exeModel        = new ExeModel();
        $mch_service     = new Mch();

        $exe = $exeModel->findWithStore(['dev_no' => $devNo]);

        if(!$exe){
            return Response::error(Code::not_exists, '设备码无效');
        }

        if($exe->status == ExeStatus::status_disabled){
            return Response::error(Code::fail, '收银台被禁用');
        }

        $mch = $mch_service->getInfo($exe->mch_id);

        Mch::ifStopThrow($exe->mch_id);

        $oprator = $cacheierService->login($userName, $password);

        if($oprator->store_id != $exe->store_id){
            return Response::error(Code::fail, '你不属于此门店');
        }

        $accessTokenService  = new AccessToken();

        $token   = $accessTokenService->build([$oprator->mch_id, $oprator->store_id, $oprator->id, $exe->id]);

        return Response::success('', [
            'username'        => $oprator->username,
            'mch_id'          => $oprator->mch_id,
            'store_id'        => $oprator->store_id,
            'store_name'      => $exe->store_name,
            'headimage'       => $oprator->headurl,
            'idcard'          => $oprator->id_card,
            'last_login_at'   => $oprator->last_login_at,
            'access_token'              => $token,
            'access_token_expires_in'   => $accessTokenService->getExpires(),
            'refund_pwd' => $mch->refund_pwd
        ]);
    }


}