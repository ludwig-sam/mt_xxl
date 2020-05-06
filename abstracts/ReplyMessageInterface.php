<?php namespace Abstracts;

abstract class ReplyMessageInterface{

    abstract  function getAttr($attr);

    abstract  function setAttr($attr, $value);

    abstract  function toArray();

    function __get($name){
        return $this->getAttr($name);
    }

}