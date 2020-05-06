<?php namespace App\Http\Middleware;


use Libs\Log;
use Libs\Response;
use Libs\Route;
use Closure;
use Illuminate\Http\Request;

abstract class  IBaseMiddleware {
    private $content;
    private $code;

    abstract protected function before(Request &$request);
    abstract protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response);

    public function handle(Request $request, Closure $next){

        Log::start('middleware_handle');

        if(!$this->before($request)){
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->setContent($this->getContent());
        }else{
            $response = $next($request);
        }

        $runtime = Log::end('middleware_handle');

        if($runtime > 1){
            Log::warning("慢日志", [
                'path'     => array_get(Route::action(), 2),
                'runtime'  => $runtime
            ]);
        }

        return $this->after($request, $response);
    }

    protected function getContent(){
        return $this->content;
    }

    protected function setContent($content){
        $this->content = $content;
    }

    protected function isSuccess(){
        return Response::setResponse($this->getCode(), '')->isSuccess();
    }

    protected function setCode($code){
        $this->code = $code;
    }

    protected function getCode(){
        return $this->code;
    }


}
