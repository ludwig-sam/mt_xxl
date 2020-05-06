<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use Providers\Curd\CurdServiceTrait;
use Providers\Single\SingleAble;
use Illuminate\Contracts\Support\Arrayable;

abstract class  BaseRow implements \Providers\Row\RowContainerInterface, Arrayable
{
    use SingleAble;
    use CurdServiceTrait;

    public function __get($name)
    {
        return $this->getRow()->getAttribute($name);
    }

    public function attr($name)
    {
        return $this->getRow()->getAttribute($name);
    }

    public function toArray()
    {
        return $this->getRow()->toArray();
    }
}