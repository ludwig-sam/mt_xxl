<?php namespace Abstracts;


interface RequestFilterInterface{
    function filter(Array $config, Offsetable &$request);
}
