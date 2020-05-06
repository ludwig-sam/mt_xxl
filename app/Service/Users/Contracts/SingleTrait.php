<?php namespace App\Service\Users\Contracts;


use Libs\Str;
use App\Service\Users\Contracts\UserAbstraict;

Trait SingleTrait{

    static private $instance;
    static function getInstance($is_mook = false) : UserAbstraict{

        $class = self::class;
        $name  = Str::last($class, '\\');

        if($is_mook){
            $class = $class . '\\Mook\\' . $name . 'Mook.php';
        }

        if(!self::$instance){
            self::$instance = new $class;
        }

        return self::$instance;
    }
}