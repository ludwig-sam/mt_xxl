<?php namespace App\Service\Wechat\Message;

use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Support\Collection;

class CardMessage extends MessageAbsctracts {


    public function transform(Collection $material) : Message
    {
        $messageService = new \App\Service\Wechat\Message();
        $messageService->card($this->msgObj->FromUserName, $material->get('card_id'));
        return new Raw('');
    }

}