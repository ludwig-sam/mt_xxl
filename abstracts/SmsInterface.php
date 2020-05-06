<?php namespace Abstracts;

interface SmsInterface{

    public function send($number, $sign, $msg, Array $params);

    public function getResponse();

    public function getRequest();

}