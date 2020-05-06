<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午11:10
 */

namespace App\Service\MessageSend\Methods\WechatMass\Types;


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
            'media_id' => $this->content['media_id']
        ];
    }

    public function getType()
    {
        return 'music';
    }

}