<?php namespace Providers;

use Abstracts\ApiResultInterface;
use Illuminate\Support\Collection;


class WechatApiResult implements ApiResultInterface {

    private $result;

    public function __construct($ret)
    {

        if(!$ret){
            $ret = '{"errcode" : 500, "errmsg" : "wechat:网络错误"}';
        }

        if($ret instanceof Collection) {
            $this->result = $ret;
            return true;
        }

        if(is_string($ret)){
            $ret        = json_decode($ret, true);
        }

        $this->result   = new Collection($ret);

        return true;
    }

    public function getData()
    {
        $data = $this->get('data');
        if($data){
            return new Collection($data);
        }
        return $this->result;
    }

    public function getMsg()
    {
        return $this->get('errmsg');
    }

    public function isSuccess()
    {
        return $this->getCode() == 0;
    }

    public function getCode()
    {
        $code = $this->get('errcode');
        if(is_null($code)){
            return 0;
        }
        return $code;
    }

    public function get($name){
        return $this->result->get($name);
    }
}