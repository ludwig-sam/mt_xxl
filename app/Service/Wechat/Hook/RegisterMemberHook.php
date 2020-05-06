<?php namespace App\Service\Wechat\Hook;




use Abstracts\ReplyMessageInterface;
use Libs\Log;
use App\Service\Member\Member;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\User;

class RegisterMemberHook  implements HookInterface {

    public function hanlder(ReplyMessageInterface $message)
    {
        $wechatUser     = new User();
        $memberService  = new Member();
        $openid         = $message->FromUserName;

        try{
            $memberService->pubLogin($wechatUser->get($openid));
        }catch (\Exception $exception){
            Log::error("registerMember 异常", [
                "openid" => $openid,
                "msg"    => $exception->getMessage()
            ]);
        }
    }
}