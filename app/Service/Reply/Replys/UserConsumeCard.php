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

class UserConsumeCard extends BaseReply implements ReplySetAble
{

    public function event()
    {
        return 'user_consume_card';
    }

    public function msgType()
    {
        return 'event';
    }

    public function crate($eventName, Collection $collection)
    {
    }

    public function update($id, Collection $collection)
    {
        throw new ReplyException("核销卡券的回复不能更改");
    }

}