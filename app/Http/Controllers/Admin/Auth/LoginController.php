<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\BaseController;
use Libs\Response;
use App\Http\Codes\Code;
use Libs\Time;
use App\Models\AdminModel;
use App\DataTypes\AdminStatus;
use App\Service\Mch\Mch;
use App\Service\Rbac\PermissionStack;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends BaseController
{
    use AuthenticatesUsers;

    public function rule()
    {

    }

    public function __construct()
    {
        parent::notNeedToken();

        parent::notNeedPermission();

        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $credentials = $request->only('user_name', 'password');

        try {
            if (! $token = \JWTAuth::attempt($credentials)) {
                return Response::error(Code::invalid_credentials, '账号密码错误');
            }
        } catch (JWTException $e) {
            return Response::error(Code::could_not_create_token, '登陆失败');
        }

        $expires = config('jwt.ttl') * 60;
        $user    = \JWTAuth::authenticate($token);

        $this->check($user);

        if($user['status'] != AdminStatus::status_normal){
            Response::error(Code::fail, '操作员状态错误');
        }

        $rbac = new \App\Service\Rbac\Rbac(new PermissionStack());
        $rbac->login($user->id);

        $admin_model = new AdminModel();
        $admin_model->find($user->id)->update(['last_login_at' => Time::date()]);

        AdminStatus::checkLogin($user->status);

        return Response::withHeaders(compact(Response::token_name))::success('登陆成功', [Response::token_name => $token, 'expires' => $expires, 'user_id' => $user->id]);
    }

    private function check($user)
    {
        if($user->mch_id){
            Mch::ifStopThrow($user->mch_id);
        }
    }
}
