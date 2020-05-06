<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/11
 * Time: 上午10:41
 */

namespace App\Service\MessageSend\Contracts;


interface MessageProviderInterface
{
    function getMessageParam();

    function getMessageTemplateName();

    function getMessageTo();
}