<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午11:10
 */

namespace App\Service\MessageSend\Methods\WechatCustomer\Types;


use App\Service\MessageSend\Contracts\MessageInterface;

class Music implements MessageInterface
{

    private $content;

    public function setMessage($to, $message)
    {
        $this->content = $message;
    }

    public function getContent()
    {
        return [
            "title"=>"MUSIC_TITLE",
            "description"=>"MUSIC_DESCRIPTION",
            "musicurl"=>"MUSIC_URL",
            "hqmusicurl"=>"HQ_MUSIC_URL",
            "thumb_media_id"=>"THUMB_MEDIA_ID"
        ];
    }

    public function getType()
    {
        return 'music';
    }
}