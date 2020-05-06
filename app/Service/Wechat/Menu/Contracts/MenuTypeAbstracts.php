<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/14
 * Time: 下午7:15
 */

namespace App\Service\Wechat\Menu\Contracts;


use App\Service\Wechat\Helper\MenuHelper;
use Illuminate\Support\Collection;

abstract class MenuTypeAbstracts
{
   abstract public function param(Collection $collection);

   abstract public function fill(Collection $collection);

   abstract public function required() : array ;

   public function getFields(Collection $collection)
   {
       $required = $this->required();

       $required && MenuHelper::required($required, $collection);

       $fields = $collection->all();
       $fill   = $this->fill($collection) ? : [];
       $param  = $this->param( $collection) ? : [];

       $result = array_merge($fields, $fill);

       $result['param'] = $param;

       return $result;
   }

}