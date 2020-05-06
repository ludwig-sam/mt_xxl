<?php

namespace App\Jobs;

use Libs\Log;
use App\Service\Reply\Receive;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPaySuccessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $message            = toArray($this->order);
        $message['MsgType'] = 'event';
        $message['Event']   = 'mt_pay';

        Log::info("ProcessPaySuccessOrder start", $message);
        $responseMessage = Receive::responseOriginalMsg($message);
        Log::info("ProcessPaySuccessOrder end", ['response' => $responseMessage->all()]);
    }

    public function failed(\Exception $exception = null)
    {
        Receive::exception($exception, 'ProcessPaySuccessOrder');
    }

}
