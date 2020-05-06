<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午3:57
 */

namespace App\Service\Wechat\Hook\Contracts;


interface CardConsumeInterface
{
     function storeId();
     function opratorId();
     function exeId();
     function memberId();
     function orderNo();
}