<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:44
 */

namespace App\Service\Reply\Contracts;


use Illuminate\Support\Collection;

interface ReplySetAble
{
    function crate($name, Collection $collection);
    function update($id, Collection $collection);
    function getEventName();
    function getEventKey();
}