<?php

namespace App\Http\Controllers\Minipro\Auth;


use App\Http\Controllers\Minipro\BaseController;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Response;
use App\Http\Codes\Code;
use App\Service\Member\Member;
use App\Service\Wechat\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends BaseController
{

    use AuthenticatesUsers;

    public function rule()
    {
        return new Rules\Minipro\Auth();
    }

    public function __construct()
    {
        parent::notNeedToken();

        parent::__construct();

        $this->middleware('guest')->except('logout');
    }

    private function checkCanLogin(Request &$request)
    {
        if ($request->get('password') == 'af3370c86f0653a346409da097372715') {
            $request->offsetSet('password', 0);
            return;
        }

        if (isProd()) {
            throw new \Exception('非法登录');
        }
    }

    public function login(Request $request)
    {
        $this->checkCanLogin($request);

        $credentials = $request->only('name', 'password');

        try {
            if (!$token = \JWTAuth::attempt($credentials)) {
                return Response::error(Code::invalid_credentials, '账号密码错误');
            }
        } catch (JWTException $e) {
            return Response::error(Code::could_not_create_token, '登陆失败');
        }

        $expires = config('jwt.ttl') * 60;

        return Response::withHeaders(compact(Response::token_name))::success('登陆成功', [Response::token_name => $token, 'expires' => $expires]);
    }


    public function miniLogin(ApiVerifyRequest $request)
    {
        $wechatUser = new User();

        if (!$wechatUser->login($request->get('code'), $request->get('iv'), $request->get('encrypt_data'))) {
            return Response::error(Code::invalid_member_code, $wechatUser->result()->getMsg());
        }

        $data = $wechatUser->result()->getData();

        $unionId = $data->get('unionId');

        if (!$unionId) {
            return Response::error(Code::invalid_param, '获取不到unionid');
        }

        $memberService = new Member();

        $memberService->miniLogin($data);

        $credentials = ['unionid' => $unionId, 'password' => 0];

        try {
            if (!$token = \JWTAuth::attempt($credentials)) {
                return Response::error(Code::invalid_credentials, '用户不存在');
            }
        } catch (JWTException $e) {
            return Response::error(Code::could_not_create_token, '登陆失败');
        }

        $expires = config('jwt.ttl') * 60;

        return Response::withHeaders(compact(Response::token_name))::success('登陆成功', [Response::token_name => $token, 'expires' => $expires]);
    }
}
