<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/3
 * Time: 下午4:10
 */

namespace App\Service\Auth;


use App\Exceptions\GatewayException;
use App\Http\Codes\Code;
use Libs\Sign;
use App\Service\Service;
use Illuminate\Support\Collection;

class SignService extends Service
{

    private $appid;
    private $time;

    private $config;

    private function trim()
    {
        return [
            null
        ];
    }

    private function getBaseData(Collection $collection)
    {
        Check::require($collection, [
            'appid',
            't'
        ]);

        $this->appid = $collection->get('appid');
        $this->time  = $collection->get('t');
    }

    private function init(Collection $collection)
    {
        $this->getBaseData($collection);
        $this->loadConfig();
        $this->checkConfig();
    }

    private function checkConfig()
    {
        if(!$this->config){
            throw new GatewayException("appid 错误");
        }
    }

    private function loadConfig()
    {
        $this->config = config('gateway.' . $this->appid, []);
    }

    private function compare($my_sign, $sign)
    {
        if($my_sign != $sign){
            throw new GatewayException("签名错误", Code::invalid_param, [
                'input_sign'    => $sign,
                'my_sign'       => $my_sign
            ]);
        }
    }

    public function check(Collection $collection, $sign_name = 'sign')
    {
        $this->init($collection);

        $sign    = $collection->get($sign_name);

        $data    = $collection->all();

        array_forget($data, $sign_name);

        $my_sign = $this->mySign($data, $this->token());

        $this->compare($my_sign, $sign);

        return true;
    }

    public function mySign($data, $token)
    {
        $data = Sign::filter($data, $this->trim());

        $data = Sign::serialize($data);

        $data = Sign::ksort($data);

        $str  = Sign::linkString($data);

        return Sign::encrypt($str, $token);
    }

    private function token()
    {
        return array_get($this->config, 'token');
    }
}