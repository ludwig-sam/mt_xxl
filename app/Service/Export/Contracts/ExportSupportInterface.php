<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/7
 * Time: 下午5:52
 */

namespace App\Service\Export\Contracts;


interface ExportSupportInterface
{
    function exportByIds($ids, $request);

    function filterNoLimit($request);

    function cells($list);
}