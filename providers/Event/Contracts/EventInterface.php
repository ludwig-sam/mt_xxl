<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午9:17
 */

namespace Providers\Event\Contracts;

interface EventInterface
{
    public function eventKey();

    public function eventName();

    public function execute($name = null);
}