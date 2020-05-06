<?php namespace App\Service\Wechat\Message;

use Libs\Time;
use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Support\Collection;

class TextMessage extends MessageAbsctracts {


    public function transform(Collection $material) : Message
    {
        $content = $material->get('content');

        $conversions = [
            'open_id'  => 'FromUserName',
            'app_id'   => 'ToUserName'
        ];

        $replaces   = [
            "date" => Time::date()
        ];

        foreach ($conversions as $name => $conversion){
            $conversions[$name] = $this->msgObj->getAttr($conversion);
        }

        $conversions = array_merge($conversions, $replaces);

        foreach ($conversions as $name => $conversion){
            $content = str_replace("[:{$name}]", $conversion, $content);
        }

        return new Text($content);
    }

}