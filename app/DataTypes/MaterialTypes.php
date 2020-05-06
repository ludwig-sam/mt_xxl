<?php namespace App\DataTypes;

use App\Exceptions\MaterialException;

class MaterialTypes{
    const text   = 'text';
    const image  = 'image';
    const music  = 'music';
    const video  = 'video';
    const voice  = 'voice';
    const article  = 'article';
    const hook   = 'hook';
    const qrcode = 'qrcode';
    const card   = 'card';
    const template   = 'template';

    public static function getTypes(){
        return [
            self::text,
            self::image,
            self::hook,
            self::qrcode,
            self::card,
            self::music,
            self::video,
            self::article,
            self::voice,
            self::template
        ];
    }

    public static function checkType($type)
    {
        if(!in_array($type, self::getTypes())){
            throw new MaterialException("获取素材时候错误的类型：" . $type);
        }
    }

    public static function checkOfficialType($type)
    {
        $types = [
            self::image,
            self::music,
            self::video,
            self::voice,
            self::article
        ];

        if(!in_array($type, $types)){
            throw new MaterialException("不属于微信永久素材：" . $type);
        }
    }
}

