<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:18
 */

namespace App\Service\MessageSend\Contracts;


interface MessageInterface
{

    function setMessage($to, $message);
    function getType();
    function getContent();
}