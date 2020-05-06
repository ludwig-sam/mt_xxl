<?php namespace App\Http\Middleware;


use App\Service\Auth\SignService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class SignMiddleware extends IBaseMiddleware {

    protected function before(Request &$request)
    {
        $this->check($request);

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        return $response;
    }

    private function check(Request &$request)
    {
        $sign_service = new SignService();

        $sign_service->check(new Collection($request), 'sign');
    }
}