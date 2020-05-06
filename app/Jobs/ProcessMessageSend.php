<?php

namespace App\Jobs;

use Abstracts\ListenerInterface;
use App\Exceptions\MessageSendException;
use Libs\Log;
use App\Models\MessageSendLogModel;
use App\DataTypes\MessageSendLogTypes;
use App\Service\MessageSend\Contracts\MessageInterface;
use App\Service\MessageSend\Contracts\SendAble;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMessageSend  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;

    private $model;
    private $sender;
    private $message;

    public $number = 1;
    public $listner;

    public function __construct(MessageSendLogModel $model, SendAble $sender, MessageInterface $message, ListenerInterface $listner)
    {
        $this->model = $model;
        $this->sender = $sender;
        $this->message = $message;
        $this->listner = $listner;
    }

    public function handle()
    {
        $this->sender->send($this->message);

        if(!$this->sender->result()->isSuccess()) throw new MessageSendException($this->sender->result()->getMsg());

        $this->model->status  = MessageSendLogTypes::status_success;
        $this->model->comment = "发送成功";
        $this->model->save();

        $this->update('success');

        return true;
    }

    public function failed(\Exception $exception = null)
    {
        $this->model->status  = MessageSendLogTypes::status_fail;
        $this->model->comment = $exception->getMessage();
        $this->model->save();

        return true;
    }

    public function update($data)
    {
        $data == 'success' && $this->listner->change(['status' => 'success']);
    }
}
