<?php

namespace App\Http\Controllers\Pub;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\SmsRule;
use Libs\Response;
use App\Service\Sms\SmsVerifyCode;


class SmsController extends BaseController
{

    public function rule()
    {
        return new SmsRule();
    }

    public function verify(ApiVerifyRequest $request)
    {
        SmsVerifyCode::verifyAndDel($request->get('mobile'), $request->get('sms_code'));

        return Response::success('验证成功');
    }

    public function onlyVerify(ApiVerifyRequest $request)
    {
        SmsVerifyCode::verify($request->get('mobile'), $request->get('sms_code'));

        return Response::success('验证成功');
    }

}
