<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/28
 * Time: 下午4:25
 */

namespace App\Service\JobApi;

use Abstracts\ApiResultInterface;
use Illuminate\Support\Collection;

class HardWorkResult implements ApiResultInterface
{

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
        }

        $this->result   = new Collection($ret);
    }

    public function getMsg()
    {
        return $this->result->get('msg');
    }

    public function getData()
    {
        return new Collection($this->result->get('data'));
    }

    public function getCode()
    {
        return $this->result->get('retcode');
    }

    public function get($name)
    {
        return $this->getData()->get($name);
    }

    public function isSuccess()
    {
        return $this->getCode() == 'success';
    }
}