<?php

namespace App\Jobs;

use Abstracts\MessageTransformInterface;
use Libs\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;

class ProcessReplyMessage  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries   = 1;

    private $messageTransform;
    private $material;


    public function __construct(MessageTransformInterface $messageTransform, Collection $material)
    {
        $this->messageTransform = $messageTransform;
        $this->material         = $material;
    }

    public function handle()
    {

        $this->messageTransform->transform($this->material);

        return true;
    }

    public function failed(\Exception $exception = null)
    {
    }
}
