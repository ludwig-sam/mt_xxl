<?php

namespace App\Service\Fans;

use Illuminate\Support\Facades\Redis;


class Cache
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    private function key()
    {
        return $this->key;
    }

    function flush()
    {
        return Redis::del($this->key());
    }

    function push($value)
    {
        return Redis::rPush($this->key(), $value);
    }

    function pop()
    {
       return Redis::lPop($this->key());
    }

    function len()
    {
        return Redis::lLen($this->key());
    }

    function limit($limit)
    {
        $number = 0;
        $list   = [];
        $limit  = min($limit, $this->len());

        while ($number < $limit){
            $list[] =  $this->pop();

            $number ++;
        }

        return $list;
    }
}