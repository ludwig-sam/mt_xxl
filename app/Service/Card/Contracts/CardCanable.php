<?php namespace App\Service\Card\Contracts;

interface CardCanable{

    function activate($info);

    function delete($id);

    function receive($code);

    function canUse();

    function consume($code, $out_str = null);

    function grant($outStr);
}