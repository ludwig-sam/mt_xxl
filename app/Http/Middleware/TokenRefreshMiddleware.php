<?php namespace App\Http\Middleware;


use Libs\Response;
use Illuminate\Http\Request;

class TokenRefreshMiddleware extends IBaseMiddleware {

    protected function before(Request &$request){

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        $old_token = $request->headers->get(Response::token_name) ? : $request->all(Response::token_name);
        $response->headers->set(Response::token_name, config('jwt.auto_refresh')  ? $this->auth->refresh() : $old_token);

        return $response;
    }

}
