<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway;



use Libs\Route;
use App\Service\Gateway\Contracts\RequestInterface;

class RequestService implements RequestInterface
{

    /**
     * @return array|\Illuminate\Http\Request|string
     */
    private function request()
    {
        return \request();
    }

    public function getIp()
    {
        return $this->request()->ip();
    }

    public function getRouteSplit()
    {
        list($controller, $method) = Route::action();

        return [$controller, $method];
    }

    public function getBodyContent()
    {
        return json_encode($this->request()->all(), JSON_UNESCAPED_UNICODE);
    }
}