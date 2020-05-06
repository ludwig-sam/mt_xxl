<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午9:47
 */

namespace App\Service\MessageSend\Methods\WechatMass\Filters;


use App\Service\MessageSend\Contracts\MessageMethodAbsctricts;

class AllFilter extends MessageMethodAbsctricts
{

    public function filter()
    {
        return [
            "filter" => [
                "is_to_all" => true,
            ]
        ];
    }
}