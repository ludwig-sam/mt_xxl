<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/10
 * Time: 上午9:41
 */

namespace App\Service\Card;


use Illuminate\Support\Facades\Redis;


class CodeService
{

    private $expires = 600;

    public function __construct($expire = null)
    {
        if($expire > 0){
            $this->expires = $expire;
        }
    }

    public function scan($code)
    {
        $name = self::getCacheName($code);

        Redis::set($name, $code);

        Redis::expire($name, $this->expires);
    }

    public function scanComplete($code)
    {
        $name = self::getCacheName($code);

        if(Redis::exists($name)){
            return (bool)Redis::del($name, $code);
        }

        return true;
    }

    public function isScan($code)
    {
        $name = self::getCacheName($code);

        return Redis::get($name) == true;
    }

    private static function getCacheName($code)
    {
        return str_replace('\\', '.', __CLASS__) . ':' . $code;
    }


}