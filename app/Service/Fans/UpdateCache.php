<?php

namespace App\Service\Fans;


class UpdateCache
{
    const key = 'mt_fans_openid_update';

    static function cache()
    {
        static $cache;

        if(!$cache){
            $cache = new Cache(self::key);
        }

        return $cache;
    }
}