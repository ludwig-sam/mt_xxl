<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    function error($msg, $type = 'error', $quit = true){
        $last_caller = $this->getCallerInfo(__FUNCTION__);

        $color    = $type == 'success' ? '0;32' : '1;31';
        $content  = "error : " . $msg . ' in ' . $last_caller['file'] . ' ' .  $last_caller['line'];
        $colorMsg = "\033[" . $color . "m" . $content . "\033[0m";
        echo ($colorMsg . "\n");
        $quit && exit;
    }

    private function getCallerInfo($fun_name){
        $traces = debug_backtrace();
        $lenth  = count($traces) - 1;
        $result = array();
        for($i = $lenth;$i>=0;$i--){
            $trace = $traces[$i];
            if($trace['function'] == $fun_name){
                $result = $trace;
                break;
            }
        }
        return $result;
    }

}
