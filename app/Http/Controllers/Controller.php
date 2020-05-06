<?php

namespace App\Http\Controllers;


use App\Http\Codes\Code;
use App\Http\Rules;
use Providers\Single\SingleAble;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Service\Oprator\OpratorLog;
use Libs\Response;


abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use SingleAble;

    public function __construct()
    {
        $rule = $this->rule();
        $rule ? Rules::registe($rule) : false;
    }

    protected function limitNum()
    {
        $limit   = \App::make('request')->query('limit');
        $default = 15;

        return is_null($limit) ? $default : max(1, (int)$limit);
    }

    static function note($title, $detial)
    {
        OpratorLog::note($title, $detial);
    }

    function response($ret, $opration_name, $data = [])
    {
        return $ret ? self::success('', $data) : self::error(Code::fail, $opration_name . '失败');
    }

    function success($msg = '', $data = [])
    {
        return Response::success($msg, $data);
    }

    function error($code, $msg)
    {
        return Response::error($code, $msg);
    }

    abstract function module();

    abstract function rule();

}
