<?php namespace Providers;

use Abstracts\ApiResultInterface;
use Illuminate\Support\Collection;


class QiniuApiResult implements ApiResultInterface {

    private $result;

    public function __construct($ret)
    {
        if(!$ret){
            $ret = '{"err" : "网络错误"}';
        }
        switch (gettype($ret)){
            case 'string':
                    $ret        = json_decode($ret, true);
                break;
            case 'array':
                break;
            default:
                throw new \Exception('qiniu api result invalid init ret type' . gettype($ret));
                break;
        }

        $this->result   = new Collection($ret);
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
        return $this->get('err');
    }

    public function isSuccess()
    {
        return is_null($this->getCode());
    }

    public function getCode()
    {
        return $this->get('err');
    }

    public function get($name){
        return $this->result->get($name);
    }

}