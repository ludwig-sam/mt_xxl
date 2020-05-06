<?php namespace Libs;

use App\Http\Codes\Code;

class Response
{
    const retcode_name = 'retcode';
    const msg_name     = 'msg';
    const data_name    = 'data';
    const token_name   = 'token';

    static private $response = [];
    static private $headers  = [];

    public function response()
    {
        return response()->json(self::$response, 200, self::$headers, JSON_UNESCAPED_UNICODE);
    }

    public function isSuccess(Array $response = null)
    {
        $theResponse = is_null($response) ? self::$response : $response;
        return $theResponse[self::retcode_name] == Code::success;
    }

    static public function success($msg = '', $data = [])
    {
        if (is_object($msg) || is_array($msg)) {
            $data = $msg;
            $msg  = '';
        }

        self::setResponse(Code::success, $msg, $data);
        return (new self)->response();
    }

    static public function error($code, $msg, $data = [])
    {
        self::setResponse($code, $msg, $data);
        return (new self)->response();
    }

    static public function fill($data)
    {
        self::$response[self::data_name] = array_merge(self::$response[self::data_name], $data);
        return (new self);
    }

    static public function returnThis($code, $msg = '', $data = [])
    {
        self::setResponse($code, $msg, $data);
        return new self;
    }

    static public function setResponse($code, $msg, $data = [])
    {
        self::$response = [
            self::retcode_name => $code,
            self::msg_name     => $msg,
            self::data_name    => $data,
            "request_id"       => IRequest::getRequestId(),
            "runtime"          => Log::end('handle_request')
        ];
        return new self();
    }

    static public function withHeaders(Array $headers)
    {
        self::$headers = $headers;
        return new self();
    }


}