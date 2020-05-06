<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午2:04
 */

namespace App\Service\Material\Materials;


use App\Exceptions\MaterialException;
use App\Models\MaterialModel;
use App\Service\Material\Contracts\GetTypeTrait;
use App\Service\Material\Contracts\MaterialAbsctracts;
use App\Service\Wechat\Media;
use Illuminate\Support\Collection;
use Providers\UploadFactory;

class MusicMaterial extends MaterialAbsctracts
{
    use GetTypeTrait;

    public function save(MaterialModel $materialModel, Collection &$material)
    {
        throw  new MaterialException('功能开放中...');
    }

    public function updateUpload(Collection &$material)
    {
        throw  new MaterialException('音乐素材不支持修改');
    }

    public function upload(Collection &$material)
    {
        throw  new MaterialException('功能开放中...');
    }
}