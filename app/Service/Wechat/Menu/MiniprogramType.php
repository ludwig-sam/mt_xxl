<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/14
 * Time: 下午7:09
 */

namespace App\Service\Wechat\Menu;


use App\Service\Wechat\Menu\Contracts\MenuTypeAbstracts;
use Illuminate\Support\Collection;

class MiniprogramType extends MenuTypeAbstracts
{

    public function required() : array
    {
        return [ 'url', 'appid', 'pagepath'];
    }

    public function param(Collection $collection)
    {
        return [
            "url"      => $collection->get('url'),
            "appid"    => $collection->get('appid'),
            "pagepath" => $collection->get('pagepath'),
        ];
    }

    public function fill(Collection $collection)
    {
        return [
            "key"      => $collection->get('appid')
        ];
    }
}