<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:47
 */

namespace App\Service\MessageSend\Methods\WechatCustomer\Filters;


use App\Service\MessageSend\Contracts\MessageMethodAbsctricts;

class OpenidFilter extends MessageMethodAbsctricts
{

    public function filter()
    {
        return [
            "touser" => is_array($this->to) ? $this->to[0] : $this->to
        ];
    }
}