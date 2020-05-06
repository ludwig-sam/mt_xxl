<?php namespace Abstracts;

interface ApiResultInterface{
    function isSuccess();
    function getCode();
    function getMsg();
    function get($name);
    /**
     * @return  \Illuminate\Support\Collection
     */
    function getData();
}
