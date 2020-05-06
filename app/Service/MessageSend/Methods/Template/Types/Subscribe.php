<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午11:10
 */

namespace App\Service\MessageSend\Methods\Template\Types;


use App\Service\MessageSend\Contracts\MessageInterface;

class Subscribe implements MessageInterface
{

    private $content;
    private $to;

    public function setMessage($to, $message)
    {
        $this->to = $to;
        $this->content = $message;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getType()
    {
        return 'Subscribe';
    }

}