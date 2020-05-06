<?php namespace App\Http\Middleware;


use Libs\Log;
use Illuminate\Http\Request;


class LogRequestMiddleware extends IBaseMiddleware {

    protected function before(Request &$request){

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){

        if(isDebug()){
            if($request->expectsJson()){
                Log::request($request, ['response' => json_decode($response->getContent(), true)]);
            }else{
                Log::request($request, ['response' => $response->getContent()]);
            }
        }

        return $response;
    }

}