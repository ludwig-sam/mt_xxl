<?php namespace App\Http\Controllers\Minipro;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\SmsRule;
use Libs\Response;
use App\Service\Sms\SmsVerifyCode;


class SmsController extends BaseController{

	public function rule(){
        return new SmsRule();
	}

    public function send(ApiVerifyRequest $request)
    {
        $smsService = new SmsVerifyCode();

        $smsService->send($request->get('mobile'));

        return Response::success('短信发送成功');
    }
}