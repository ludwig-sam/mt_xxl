<?php namespace App\Service\Wechat\Message;

use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Support\Collection;

class DefaultMessage extends MessageAbsctracts {

    public function transform(Collection $material) : Message
    {
        return new Raw('');
    }

}