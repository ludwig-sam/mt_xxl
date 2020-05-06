<?php namespace App\Service\Wechat;


use App\Exceptions\WechatException;
use App\Http\Codes\Code;

class Template extends Wechat {

    public function getList(){
        $result = $this->serve()->template_message->getPrivateTemplates();

        if(!$this->parseResult($result)->isSuccess()){
            throw new WechatException($this->result()->getMsg(), Code::wechat_error);
        }

        return $this->result()->getData()->get('template_list', []);
    }

}