<?php

namespace Libs\Payments\Helper;


use Libs\Payments\Contracts\Config;
use Providers\RequestClient\HasHttpRequest;

abstract class Support
{

    use HasHttpRequest;

    private $config;

    final protected function __construct(Config $config)
    {
        $this->config = $config;
    }

    function getBaseUri()
    {
        return $this->config->getBaseUri();
    }

    function getTimeout()
    {
        return 8;
    }

    public function querys()
    {
        return [];
    }

    function options()
    {
        return [
            'verify' => false
        ];
    }

    abstract static function getInstance(Config $config);
}