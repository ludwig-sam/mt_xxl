<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/15
 * Time: 下午5:58
 */

namespace Providers\Row;


use Illuminate\Database\Eloquent\Model;

interface RowContainerInterface
{
    function getRow():Model;

    function attr($name);
}