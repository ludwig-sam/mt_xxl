<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/3
 * Time: 下午4:10
 */

namespace App\Service\Auth;


use App\Exceptions\AuthException;
use App\Http\Codes\Code;
use Libs\Response;
use Libs\Route;
use Libs\Str;
use App\Service\Rbac\Rbac;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;

class Intercept extends Service
{

    public function verify()
    {
        return $this->getAuthenticatedUser();
    }

    public function verifyPermission($module, UserAbstraict $user)
    {
        list($controller, $method) = Route::action();

        $route = [$module, Str::lcfist($controller), $method];

        if(!Rbac::check($user->getId(), join('.', $route), $user->isSuper())){
            throw new AuthException('权限不足', Code::permission_denied);
        }
    }

    private function getAuthenticatedUser()
    {
        try {

            if ($user = \JWTAuth::parseToken('bearer', Response::token_name)->authenticate()) {
                return $user;
            }

            throw new AuthException("未找到用户", Code::token_invalid);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            throw new AuthException("token 过期", Code::token_expired);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            throw new AuthException("token 无效", Code::token_invalid);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            throw new AuthException("验证失败", Code::token_invalid);
        }
    }
}