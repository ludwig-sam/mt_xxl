<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:07
 */

namespace App\Service\MessageSend\Contracts;


use Abstracts\ApiResultInterface;

interface  SendAble
{
    /**
     * @return ApiResultInterface
     */
    function result();
    function send(MessageInterface $message);
}