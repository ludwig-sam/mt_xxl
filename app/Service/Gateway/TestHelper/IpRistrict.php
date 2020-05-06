<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway\TestHelper;


class IpRistrict extends \App\Service\Gateway\Ristricts\IpRistrict
{

    private $black_list = [];

    public function getBlackList()
    {
        return $this->black_list;
    }

    public function setBlackList(Array $list)
    {
        $this->black_list = $list;
    }
}