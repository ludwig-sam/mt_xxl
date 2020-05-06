<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/15
 * Time: 下午12:37
 */

namespace App\Service\Wechat\Message\Contracts;

use Abstracts\ReplyMessageInterface;
use Abstracts\MessageTransformInterface;


abstract class MessageAbsctracts  implements MessageTransformInterface
{

    protected $msgObj;

    public function __construct(ReplyMessageInterface $msgObj)
    {
        $this->msgObj = $msgObj;
    }
}