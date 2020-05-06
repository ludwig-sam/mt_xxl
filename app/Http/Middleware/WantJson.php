<?php namespace App\Http\Middleware;

use Illuminate\Http\Request;

class WantJson extends IBaseMiddleware {

    protected function before(Request &$request){
        $request->headers->add([
            'Accept' => "application/json;"
        ]);

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        return $response;
    }
}
