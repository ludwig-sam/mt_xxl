<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午12:13
 */

namespace App\Service\Material\Contracts;

use App\Models\MaterialModel;
use App\Service\Traits\ResultTrait;
use Illuminate\Support\Collection;

abstract class MaterialAbsctracts
{
    use ResultTrait;
    use MaterialComFunTrait;

    abstract function save(MaterialModel $materialModel, Collection &$material);
    abstract function upload(Collection &$material);
    abstract function updateUpload(Collection &$material);
    abstract function getType($ext   = 'Material');
}