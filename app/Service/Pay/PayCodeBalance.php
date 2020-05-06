<?php namespace App\Service\Pay;

use App\Service\Auth\MemberCode;
use App\Service\Pay\Contracts\PaycodeMatchAble;
use App\Service\Users\Contracts\UserAbstraict;

class PayCodeBalance implements PaycodeMatchAble
{
    const prefix = 3;

    public function generate(UserAbstraict $user, $len = 10, $expire = 600)
    {
        $member_code = new MemberCode($len, $expire);
        $code        = $member_code->encode($user->getId());
        return $this->addPrefix($code);
    }

    public function isMe($code)
    {
        return substr($code, 0, strlen(self::prefix)) == self::prefix;
    }

    public function addPrefix($code)
    {
        return self::prefix . $code;
    }

    public function removePrefix($code)
    {
        return substr($code, strlen(self::prefix));
    }
}

