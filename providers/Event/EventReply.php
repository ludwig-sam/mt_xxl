<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午9:11
 */

namespace Providers\Event;


use Providers\Event\Contracts\EventInterface;
use Providers\Hook\Config;
use Providers\Hook\Contracts\HookMessageContract;
use Providers\Hook\Hook;
use Libs\Arr;

class EventReply
{

    private $message;
    private $event;

    public function __construct(EventInterface $event, HookMessageContract $message)
    {
        $this->event   = $event;
        $this->message = $message;
    }

    private function getReplys()
    {
        return [];
    }

    public function response()
    {
        $replys      = $this->getReplys();
        $hooks       = Arr::pullAll($replys, 'hook', 'type');
        $hook_handle = new Hook();
        $hook_config = new Config($this->event);

        $hook_config->register($hooks);

        $hook_handle->handle($hook_config, $this->message);

        return true;
    }

}