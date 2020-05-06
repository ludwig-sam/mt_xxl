<?php

namespace App\Exceptions;

use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\Code;
use Libs\Log;
use Libs\Response;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\MessageBag;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];


    public function report(Exception $exception)
    {

        if ($this->ifBoundSentry()) {
            app('exception')->captureException($exception);
        }

        parent::report($exception);
    }


    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {

            $code = (string)$exception->getCode();

            if ($exception instanceof ExceptionCustomCodeAble) {
                $code = (string)$exception->getCustomCode();
            }

            switch (true) {
                case $exception instanceof \Illuminate\Validation\ValidationException:
                    $message_bag = new MessageBag($exception->errors());

                    return Response::error(Code::validate_fail, '验证失败:' . $message_bag->first());
                    break;
                case $exception instanceof MethodNotAllowedHttpException:
                    return $this->returnException($exception, Code::method_not_allowed_http_exception, '无效的请求方式', ['method' => $request->getMethod()]);
                    break;
                case $this->isHttpException($exception):
                    return $this->returnException($exception, Code::http_exception, '404', ['url' => $request->getRequestUri()]);
                    break;
                case $exception instanceof \ErrorException:
                case $exception instanceof FatalThrowableError:
                    Log::error('系统异常', $exception);
                    return $this->returnException($exception, Code::sys_err, '网络错误' . $exception->getMessage());
                    break;
                default:
                    return $this->returnException($exception, $code, $exception->getMessage());
                    break;
            }
        }

        return parent::render($request, $exception);
    }

    private function ifBoundSentry()
    {
        return app()->bound('exception');
    }

    public function returnException(Exception $exception, $code, $msg, Array $data = [])
    {
        if (isDebug()) {
            $exceptionInfo = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
            ];

            if (!isProd()) {
                $data = array_merge($data, $exceptionInfo);
            }
        }

        if ($exception instanceof ExceptionCustomCodeAble) {
            $data = array_merge($data, (array)$exception->getRow());
        }

        return Response::error($code, $msg, $data);
    }

}
