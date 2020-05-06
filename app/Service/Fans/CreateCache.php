<?php

namespace App\Service\Fans;


class CreateCache
{
    const key = 'mt_fans_openid';

    static function cache()
    {
        static $cache;

        if(!$cache){
            $cache = new Cache(self::key);
        }

        return $cache;
    }
}