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

class ImageMaterial extends MaterialAbsctracts
{
    use GetTypeTrait;

    public function save(MaterialModel $materialModel, Collection &$material)
    {
        $mdata = [
            'title' => $material->get('title'),
            'mp_media_id' => $material->get('media_id')
        ];

        if($materialModel->id){
            throw  new MaterialException('图片素材不支持修改');
        }

        return $materialModel->add($this->getType(), [
            'media_id'  => $material->get('media_id'),
            'media_url' => $material->get('media_url', ''),
        ], $mdata);
    }

    public function updateUpload(Collection &$material)
    {
        throw  new MaterialException('图片素材不支持修改');
    }

    public function upload(Collection &$material)
    {
        $mediaService  = new Media();

        $base64File    = $material->get('image_file');

        $uploader      = UploadFactory::image($base64File, 'image_file');

        if(!$uploader->up()){
            $this->setError($uploader->getError());
            return false;
        }

        $path = $uploader->getUploadedInfo()['path'];

        if(!$mediaService->uploadImage($path)){
            $this->setError($mediaService->result()->getMsg());
            return false;
        }

        $this->cdn()->uploadFile($path);

        $url = $this->cdn()->result()->getData()->get('path');

        $material->offsetSet('media_id', $mediaService->result()->getData()->get('media_id'));
        $material->offsetSet('media_url', $url);

        unlink($path);

        return true;
    }
}