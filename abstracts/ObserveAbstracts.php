<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/10
 * Time: 上午9:57
 */

namespace Abstracts;


use Libs\Log;

abstract class ObserveAbstracts
{

    protected $listeners = [];

    abstract function update($data);

    function notify($data)
    {
        foreach ($this->listeners as $listener){
            $listener instanceof ListenerInterface && $listener->change($data);
        }
    }

    function registe(ListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    function unRegiste(ListenerInterface $listener)
    {
        foreach ($this->listeners as  $k => $obj){
            if($obj instanceof $listener){
                unset($this->listeners[$k]);
            }
        }
    }

    function getListeners()
    {
        return $this->listeners;
    }
}