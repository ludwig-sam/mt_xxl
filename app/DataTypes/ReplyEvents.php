<?php namespace App\DataTypes;



use App\Exceptions\ReplyException;

class ReplyEvents{
    const text      = 'text';
    const subscribe = 'subscribe';
    const menu      = 'menu';


    public static function getTypes()
    {
        return [self::text, self::subscribe, self::menu];
    }

    public static function checkType($type)
    {
        if(!in_array($type, self::getTypes())){
            throw new ReplyException("暂不支持的回复：" . $type);
        }
    }
}

