<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/14
 * Time: 下午7:09
 */

namespace App\Service\Wechat\Menu;


use App\DataTypes\WechatMenuTypes;
use App\Service\Wechat\Menu\Contracts\MenuTypeAbstracts;
use Illuminate\Support\Collection;

class DefaultType extends MenuTypeAbstracts
{

    public function required() : array
    {
        return [];
    }

    public function param(Collection $collection)
    {
        WechatMenuTypes::check($collection->get('type'));
    }

    public function fill(Collection $collection)
    {
    }
}