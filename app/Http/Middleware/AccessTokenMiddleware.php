<?php namespace App\Http\Middleware;


use App\Service\Token\AccessToken;
use App\Service\Users\CachierUser;
use Illuminate\Http\Request;


class AccessTokenMiddleware extends IBaseMiddleware {

    protected function before(Request &$request){

        $accessTokenService = new AccessToken();
        $data = $accessTokenService->verify(request()->get('access_token'));

        CachierUser::getInstance()->init($data);

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        return $response;
    }

}