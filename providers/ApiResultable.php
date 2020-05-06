<?php namespace Providers;

use Abstracts\ApiResultInterface;

/**
 * Traits ApiResultable
 * @property ApiResultInterface $result;
 * @package Providers
 */

Trait ApiResultable{
    public $result;

    abstract function newResult($ret);

    final protected function parseResult($ret) : ApiResultInterface{
        $this->result  = $this->newResult($ret);
        return $this->result;
    }

    final public function result() : ApiResultInterface{
        if(!$this->result)$this->result = $this->newResult('');
        return $this->result;
    }
}