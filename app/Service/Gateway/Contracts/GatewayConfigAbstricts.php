<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:32
 */

namespace App\Service\Gateway\Contracts;


use Illuminate\Support\Str;

abstract class GatewayConfigAbstricts
{

    public function __construct()
    {
        $this->reloadConfig();
    }

    public function getBlackIpList($default)
    {
        return $this->get('ip_black_list', $default);
    }

    public function get($field, $default = null)
    {
        $method = Str::studly($field);
        $value  = array_get($this->config(), $field);

        if(method_exists($this, $method)){
            $value = $this->$method($value);
        }

        return is_null($value) ? $default : $value;
    }

    abstract function config();

    abstract function reloadConfig();

    abstract function set($field, $value);
}

