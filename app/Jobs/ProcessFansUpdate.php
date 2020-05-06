<?php

namespace App\Jobs;

use Abstracts\ListenerInterface;
use App\Service\Fans\UpdateCache;
use App\Service\Fans\Updating;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessFansUpdate  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;

    public $timeout = 100;

    public $listner;

    public function __construct(ListenerInterface $listener)
    {
        $this->listner = $listener;
    }

    public function handle()
    {
        $updating = new Updating();

        if (UpdateCache::cache()->len()){
            $updating->update();

            $this->continue();

            return true;
        }

        $this->end();

        return true;
    }

    public function failed(\Exception $exception = null)
    {
        $this->end();

        return true;
    }

    private function continue()
    {
        dispatch(new self($this->listner));
    }

    private function end()
    {
        $this->listner->change(1);
    }
}
