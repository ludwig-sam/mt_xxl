<?php namespace Abstracts;

interface UploaderInterface{
    function uploadFile($file);
    function uploadString($string, $file_name = null);
    function result():ApiResultInterface;
}
