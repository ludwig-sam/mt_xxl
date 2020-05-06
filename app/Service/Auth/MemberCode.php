<?php

namespace App\Service\Auth;


use App\Exceptions\AuthException;
use Libs\Google2FA;

class MemberCode
{

    private $key          = 'LFLFMU2SGVCUIUCZKBMEKRKLIY';
    private $expire       = 600;
    private $len          = 10;
    private $shared_prime = 99999991;

    public function __construct($len = null, $expire = null, $key = null, $shared_prime = null)
    {
        if ($key) $this->key = $key;
        if ($expire) $this->expire = $expire;
        if ($len) $this->len = $len;
        if ($shared_prime) $this->shared_prime = $shared_prime;
    }

    public function encode($uid)
    {
        $TimeStamp = Google2FA::get_timestamp($this->expire);
        $secretkey = Google2FA::base32_decode($this->key);
        return Google2FA::oath_hotp($secretkey, $TimeStamp, $uid, $this->len, $this->shared_prime);
    }

    public function decode($otp)
    {
        $uid = bcmod($otp, $this->shared_prime);

        if (!Google2FA::verify_key($this->key, $otp, $uid, $this->expire, $this->len, $this->shared_prime)) {
            throw new AuthException("会员码无效或已过期");
        }

        return $uid;
    }

}