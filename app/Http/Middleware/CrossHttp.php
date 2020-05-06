<?php namespace App\Http\Middleware;


use Illuminate\Http\Request;

class CrossHttp extends IBaseMiddleware {

    protected function before(Request &$request){
        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        return $response;
    }


}
