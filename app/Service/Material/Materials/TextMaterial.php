<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午2:04
 */

namespace App\Service\Material\Materials;


use App\Models\MaterialModel;
use App\Service\Material\Contracts\GetTypeTrait;
use App\Service\Material\Contracts\MaterialAbsctracts;
use Illuminate\Support\Collection;

class TextMaterial extends MaterialAbsctracts
{
    use GetTypeTrait;

    public function save(MaterialModel $materialModel, Collection &$material)
    {
        $mdata = [
            'title' => $material->get('title')
        ];

        if($materialModel->id){
            return $materialModel->edit($materialModel->id, [
                'content' => $material->get('content')
            ], $mdata);
        }

        return $materialModel->add($this->getType(), [
            'content' => $material->get('content')
        ], $mdata);
    }

    public function updateUpload(Collection &$material)
    {
        return true;
    }

    public function upload(Collection &$material)
    {
        return true;
    }

}