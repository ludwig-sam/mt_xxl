<?php namespace App\Service\Wechat\Message;


use App\Exceptions\ReplyException;
use Libs\Log;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImageMessage extends MessageAbsctracts {


    public function transform(Collection $material) : Message
    {
        return new Image($material->get('media_id'));
    }

}

