<?php

namespace App\Service\Material\Materials;


use App\Models\MaterialModel;
use App\Service\Material\Contracts\GetTypeTrait;
use App\Service\Material\Contracts\MaterialAbsctracts;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TemplateMaterial extends MaterialAbsctracts
{
    use GetTypeTrait;

    public function save(MaterialModel $materialModel, Collection &$material)
    {
        if($materialModel->id){
            return $this->update( $materialModel, $material);
        }

        return $this->add($materialModel, $material);
    }

    private function rule()
    {
        return [
            "template_id" => "required",
            "data"        => "required|array"
        ];
    }

    private function message()
    {
        return [
            "template_id.required" => "模版ID必填",
            "data.required"        => "data不能为空",
            "data.array"           => "data是无效的数组",
        ];
    }

    private function validate($data)
    {
        $validator = \validator($data, $this->rule(), $this->message());

        if($validator->fails()){
            throw new  ValidationException($validator);
        }
    }

    private function getInputData(Collection $material)
    {
        $this->validate($material->all());

        $mdata = [
            'title' => $material->get('title')
        ];

        $template_id          = $material->get('template_id');
        $miniprogram_pagepath = $material->get('miniprogram_pagepath');

        $param       = [
            'template_id' => $template_id,
            'data'        => $material->get('data')
        ];

        $content = [
            'template_id'           => $template_id,
            'miniprogram_pagepath'  => $miniprogram_pagepath,
            'param'                 => $param
        ];

        return [$mdata, $content];
    }

    private function add(MaterialModel $materialModel, Collection &$material)
    {
        list($mdata, $content) = $this->getInputData($material);

        return $materialModel->add($this->getType(), $content, $mdata);
    }

    private function update(MaterialModel $materialModel, Collection &$material)
    {
        list($mdata, $content) = $this->getInputData($material);

        return $materialModel->edit($materialModel->id, $content, $mdata);
    }

    public function updateUpload(Collection &$material)
    {
        return true;
    }

    public function upload(Collection &$material)
    {
        return true;
    }

    public function getFilter($row)
    {
        $row->param = json_decode($row->param, true);
        return $row;
    }

}