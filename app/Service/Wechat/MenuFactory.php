<?php namespace App\Service\Wechat;



use App\Service\Wechat\Menu\Contracts\MenuTypeAbstracts;
use App\Service\Wechat\Menu\DefaultType;
use Illuminate\Support\Str;

class MenuFactory{

    private static $instance = [];

    public static function make($type) : MenuTypeAbstracts
    {
        if(isset(self::$instance[$type]))return self::$instance[$type];

        $class = self::getClass($type);
        self::$instance[$type] = new $class;
        return self::$instance[$type];

    }

    private static function getClass($type)
    {
        $class = __NAMESPACE__ . '\\Menu\\' . Str::studly($type)  . 'Type';

        if(class_exists($class)){
            return $class;
        }

        return DefaultType::class;
    }


}