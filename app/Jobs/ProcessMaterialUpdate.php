<?php

namespace App\Jobs;

use Abstracts\ListenerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Service\Material\PullingFactory;

class ProcessMaterialUpdate  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;

    public $timeout = 100;

    public $listner;

    public $type;

    public $start;

    public function __construct(ListenerInterface $listener, $start, $type)
    {
        $this->listner = $listener;
        $this->type    = $type;
        $this->start   = $start;
    }

    public function handle()
    {
        $limit = 20;

        $pull_hanlder = PullingFactory::make($this->type, false);

        if ($this->start == 0 || $this->start <= $pull_hanlder->getCount()){

            $pull_hanlder->pull($this->start, $limit);

            $this->start += $limit;

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
        dispatch(new self($this->listner, $this->start, $this->type));
    }

    private function end()
    {
        $this->listner->change(1);
    }
}
