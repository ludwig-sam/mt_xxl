<?php

namespace App\Service\JobApi;


use App\Service\JobApi\Model\ConfigModel;
use App\Service\JobApi\Model\MessageModel;
use App\Service\Service;
use Illuminate\Support\Collection;
use Providers\ApiResultable;
use Providers\HasRequestTrait;

class HardWork extends Service
{

    use HasRequestTrait;
    use ApiResultable;

    private $config;


    public function __construct()
    {
        $this->config = new ConfigModel();

        $this->setOptions((array)config('hard_work.base'));
    }

    private function setOptions($options)
    {
        foreach($options as $name => $val){
            $this->config->$name = $val;
        }
    }

    public function newResult($ret)
    {
        return new HardWorkResult($ret);
    }

    public function getBaseUri()
    {
        return $this->config->host;
    }

    public function create(MessageModel $messageModel)
    {
        $data = new Collection($messageModel);

        $ret = $this->post('create', $data->toArray());

        return $this->parseResult($ret)->isSuccess();
    }
}