<?php namespace Abstracts;

interface Offsetable{

    function offsetSet($name, $value);

    function offsetGet($name);

    function offsetExists($name);

    function save();

    function all();

}