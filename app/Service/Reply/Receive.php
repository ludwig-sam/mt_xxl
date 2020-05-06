<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/6
 * Time: 下午4:51
 */

namespace App\Service\Reply;

use App\Service\Wechat\Conditions\Eque;
use App\Service\Wechat\Conditions\Greater;
use App\Service\Wechat\Conditions\Less;
use App\Service\Wechat\Conditions\Preg;
use Providers\ReplyReceiveMessage;
use Libs\Log;
use App\Service\Wechat\Reply;

class Receive
{
    public static  function responseOriginalMsg($message)
    {
        $reply = new Reply(new ReplyReceiveMessage($message));

        $reply->addCondition(new Eque());
        $reply->addCondition(new Less());
        $reply->addCondition(new Greater());
        $reply->addCondition(new Preg());

        return $reply->response();
    }

    public static function exception(\Exception $exception, $root)
    {
        Log::error($root . "_exception", [
            'file'  => $exception->getFile(),
            'line'  => $exception->getLine(),
            'msg'   => $exception->getMessage(),
            'code'  => $exception->getCode()
        ]);
    }
}