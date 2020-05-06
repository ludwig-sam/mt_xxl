<?php namespace App\Service\Rbac;


use App\Service\Rbac\Contracts\PermissionInterface;
use Illuminate\Support\Facades\Redis;

class Rbac{
    const permission_key      = 'mt_xx_permision_key_';
    static private $expire_in = 7200;
    private static $permission;


    public function __construct(PermissionInterface $permission, $expire = null)
    {
        self::$permission = $permission;
        $this->setExpireIn($expire);
    }

    private function setExpireIn($expire)
    {
        if($expire > 0){
            self::$expire_in = $expire;
        }
    }

    public static function check($userId, $route, $isSupper = false)
    {
        return $isSupper || in_array($route, self::getPermissionsFromCache($userId));
    }

    public static function savePermissions($userId, $permissions)
    {
        $key = self::permission_key . $userId;
        Redis::set($key, json_encode($permissions));
        Redis::expire($key, self::$expire_in);
    }

    public static function getPermissionsFromCache($userId)
    {
        $key = self::permission_key . $userId;
        if(!Redis::exists($key)){
            self::reLogin($userId);
        }
        return json_decode(Redis::get($key), true);
    }

    private static function reLogin($userId)
    {
        self::savePermissions($userId, self::getPermissions($userId));
    }

    public function login($userId)
    {
        self::reLogin($userId);
    }

    private static function getPermissions($userId)
    {
        return array_column(self::permission()->getPermissions($userId), 'node_path');
    }

    private static function permission()
    {
        if(is_null(self::$permission)){
            self::$permission = new PermissionStack();
        }
        return self::$permission;
    }
}