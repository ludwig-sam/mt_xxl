<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/14
 * Time: 下午7:09
 */

namespace App\Service\Wechat\Helper;


use App\Exceptions\ParamException;
use Libs\Str;
use Libs\Tree;
use Illuminate\Support\Collection;

class MenuHelper
{

    static function generateKey()
    {
        return substr(time(), -4) . Str::rand(28);
    }

    static function required($keys, Collection $collection)
    {
        foreach ($keys as $key){
            if(!$collection->get($key)){
                throw new ParamException("缺少参数：" . $key);
            }
        }
    }

    static function toButton($list, $pid = 0)
    {
        $result = [];
        foreach ($list as $row){

            $button   = [
                "name" => $row['name'],
                'key'  => $row['key'],
                'type' => $row['type']
            ];

            $button = array_merge($button, $row['param']);

            if($row['pid'] == $pid){
                $button['sub_button'] = self::toButton($list, $row['id']);
                $result[] = $button;
            }
        }

        return $result;
    }

}