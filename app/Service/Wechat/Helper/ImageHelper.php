<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/14
 * Time: 下午7:09
 */

namespace App\Service\Wechat\Helper;


class ImageHelper
{

    public static function isWxImage($value)
    {
        $hosts = [
            "http://mmbiz.qpic.cn",
            "https://mmbiz.qpic.cn",
        ];

        foreach ($hosts as $host){
            if( substr($value, 0, strlen($host)) == $host)
                return true;

        }

        return false;
    }

    public static function decode($url)
    {
        $prefix = '/mp_media?mp=';

        if(substr($url, 0, strlen($prefix)) == $prefix){
            $url = substr($url, strlen($prefix) - 1);
        }

        return $url;
    }

    public static function encode($url)
    {
        if(self::isWxImage($url)){
            $url = '/mp_media?mp=' . $url;
        }

        return $url;
    }
}