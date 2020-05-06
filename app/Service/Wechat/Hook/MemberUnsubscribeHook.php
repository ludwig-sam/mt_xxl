<?php namespace App\Service\Wechat\Hook;




use Abstracts\ReplyMessageInterface;
use App\Service\Member\Member;
use App\Service\Wechat\Hook\Contracts\HookInterface;

class MemberUnsubscribeHook  implements HookInterface {

    public function hanlder(ReplyMessageInterface $message)
    {
        $memberService  = new Member();
        $openid         = $message->FromUserName;

        $memberService->subscribe($openid, 0);
    }
}