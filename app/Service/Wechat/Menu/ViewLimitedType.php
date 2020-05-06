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

class ViewLimitedType extends MenuTypeAbstracts
{

    public function required() : array
    {
        return ['media_id'];
    }

    public function param(Collection $collection)
    {
        return [
            'media_id' => $collection->get('media_id')
        ];
    }

    public function fill(Collection $collection)
    {
        return [
            'key' => $collection->get('media_id')
        ];
    }
}