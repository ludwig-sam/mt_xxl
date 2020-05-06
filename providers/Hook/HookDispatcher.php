<?php namespace Providers\Hook;


use Providers\Hook\Contracts\HookInterface;
use Providers\Hook\Contracts\HookMessageContract;

class HookDispatcher
{

    private function syncHandle(HookInterface $hanlder, HookMessageContract $message)
    {
        $hanlder->handle($message);
    }

    private function asyncHandle(HookInterface $hanlder, ConfigOption $option, HookMessageContract $message)
    {
        dispatch(new HookJob($hanlder, $message))->delay((int)$option->delay);
    }

    public function dispatch(HookInterface $handle, ConfigOption $option, HookMessageContract $message)
    {
        if ($option->is_async) {
            $this->asyncHandle($handle, $option, $message);
        } else {
            $this->syncHandle($handle, $message);
        }
    }
}

