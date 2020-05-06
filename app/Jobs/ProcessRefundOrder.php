<?php

namespace App\Jobs;

use Illuminate\Database\Eloquent\Model;
use Libs\Log;
use App\Service\Reply\Receive;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessRefundOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Model $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $message = $this->order->toArray();

        $message['MsgType'] = 'event';
        $message['Event']   = 'mt_refund';

        Log::info("ProcessRefundOrder start", $message);
        $responseMessage = Receive::responseOriginalMsg($message);
        Log::info("ProcessRefundOrder end", ['response' => $responseMessage->all()]);
    }

    public function failed(\Exception $exception = null)
    {
        Receive::exception($exception, 'ProcessRefundOrder');
    }

}
