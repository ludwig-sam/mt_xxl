<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/3
 * Time: 下午8:42
 */

namespace App\Service\Export\Contracts;


interface Exportable
{
    public function export($headers, $list, $save = false);

    public function setFileName($fileName);
}