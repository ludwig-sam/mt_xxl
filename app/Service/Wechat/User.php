<?php namespace App\Service\Wechat;


use App\Exceptions\WechatException;

class User  extends Wechat {


    public function login($code, $iv, $enData)
    {
        $session = $this->miniServe()->auth->session($code);

        $this->parseResult($session);

        if(!$this->result()->isSuccess())return false;

        return $this->parseResult($this->miniServe()->encryptor->decryptData($session['session_key'], $iv, $enData))->isSuccess();
    }

    public function get($openid)
    {
        $this->parseResult($this->serve()->user->get($openid));

        if(!$this->result()->isSuccess()){
            throw new WechatException($this->result()->getMsg());
        }

        return $this->result()->getData();
    }

    public function openidToUnionid($openid)
    {
        return $this->get($openid)->get('unionid');
    }

    public function getOpenidList(string $nextOpenId = null)
    {
        $this->parseResult($this->serve()->user->list($nextOpenId));

        if(!$this->result()->isSuccess()){
            throw new WechatException($this->result()->getMsg());
        }

        return $this->result()->getData();
    }


}