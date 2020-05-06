<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/9
 * Time: 下午4:00
 */

namespace Providers\Hook;


use Providers\Event\Contracts\EventInterface;

class Config
{
    private $_config  = [];
    private $_dynamic = [];

    public function __construct(EventInterface $event)
    {
        $this->_config = config('hook.' . $event->eventName()) ?: [];
    }

    public function register($config)
    {
        if (isset($config['name'])) {
            $this->_dynamic[] = $config;
            return;
        }

        $this->_dynamic = array_merge($this->_dynamic, $config);
    }

    public function fixed()
    {
        return $this->_config;
    }

    public function dynamic()
    {
        return $this->_dynamic;
    }

    public function config()
    {
        return array_merge($this->fixed(), $this->dynamic());
    }
}