<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午11:10
 */

namespace App\Service\MessageSend\Methods\WechatCustomer\Types;


use App\Service\MessageSend\Contracts\MessageInterface;

class Card implements MessageInterface
{

    private $content;

    public function setMessage($to, $message)
    {
        $this->content = $message;
    }

    public function getContent()
    {
        return [
            'card_id' => $this->content['card_id']
        ];
    }

    public function getType()
    {
        return 'wxcard';
    }

}