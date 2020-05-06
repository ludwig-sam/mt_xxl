<?php namespace Abstracts;

interface UploaderProcessInterface{
   function save($path);
   function parse($file);
   function getCode();
   function getError();
}
