<?php

namespace App\Jobs;

use Abstracts\ListenerInterface;
use App\Service\Fans\CreateCache;
use App\Service\Fans\Updating;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessFansCreate  implements ShouldQueue
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

        if(CreateCache::cache()->len()){
            $updating->create();
            $this->continue();

            return true;
        }

        $this->next();

        return true;
    }

    public function failed(\Exception $exception = null)
    {
        $this->next();

        return true;
    }

    public function continue()
    {
        dispatch(new self($this->listner));
    }

    public function next()
    {
        $updating = new Updating();
        $updating->saveUpdateOpenid();

        dispatch(new ProcessFansUpdate($this->listner));
    }
}
