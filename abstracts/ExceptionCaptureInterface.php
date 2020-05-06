<?php namespace Abstracts;

interface ExceptionCaptureInterface{
    public function captureException(\Exception $exception, $isError = false, $vars = null);
}