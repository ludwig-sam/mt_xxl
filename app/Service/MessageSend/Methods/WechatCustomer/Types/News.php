<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午11:10
 */

namespace App\Service\MessageSend\Methods\WechatCustomer\Types;


use App\Service\MessageSend\Contracts\MessageInterface;

class News implements MessageInterface
{

    private $content;

    public function setMessage($to, $message)
    {
        $this->content = $message;
    }

    public function getContent()
    {
        return [
            "articles"=>[
                [
                    "title"        =>"Happy Day",
                    "description"  =>"Is Really A Happy Day",
                    "url"          =>"URL",
                    "picurl"       =>"PIC_URL"
                ],
                [
                    "title"        =>"Happy Day",
                    "description"  =>"Is Really A Happy Day",
                    "url"          =>"URL",
                    "picurl"       =>"PIC_URL"
                ]
            ]
        ];
    }

    public function getType()
    {
        return 'news';
    }

}