<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/3
 * Time: 下午4:10
 */

namespace App\Service\Auth;



use App\Exceptions\GatewayException;
use App\Http\Codes\Code;
use Illuminate\Support\Collection;

class Check
{
    public static function require(Collection $data, $requireds = [])
    {
        foreach ($requireds as $required){
            if(is_null($data->get($required))){
                throw new GatewayException("缺少参数", Code::invalid_param, [
                    $required
                ]);
            }
        }
    }

}