<?php namespace Providers;

use Abstracts\ApiResultInterface;


abstract class Api{

    use ApiResultable;

    protected $options = [];

    protected function setOptions($opations){
        foreach ($opations as $k => $v){
            $this->options[$k] = $v;
        }
    }
}