<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:48
 */

namespace App\Service\Reply\Replys;


use App\Exceptions\ReplyException;
use App\Service\Reply\Contracts\BaseReply;
use App\Service\Reply\Contracts\ReplySetAble;
use Illuminate\Support\Collection;

class MtPayReply extends BaseReply implements ReplySetAble
{

    public function event()
    {
        return 'mt_pay';
    }

    public function msgType()
    {
        return 'event';
    }

    public function crate($eventName, Collection $collection)
    {
        throw new ReplyException("mt支付的回复不能更改");
    }

    public function update($id, Collection $collection)
    {
        throw new ReplyException("mt支付的回复不能更改");
    }

}