<?php namespace Providers\Hook;

use Providers\Hook\Contracts\HookInterface;
use Providers\Hook\Contracts\HookMessageContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 1;
    public $timeout = 30;

    public $hook;

    public $message;

    public function __construct(HookInterface $hook, HookMessageContract $message)
    {
        $this->hook    = $hook;
        $this->message = $message;
    }

    public function handle()
    {
        $this->hook->handle($this->message);
    }
}

