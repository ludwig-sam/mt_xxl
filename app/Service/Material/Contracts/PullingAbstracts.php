<?php

namespace App\Service\Material\Contracts;


use App\Service\Wechat\Contracts\MediaInterface;
use App\Service\Wechat\Media;
use App\Models\MaterialModel;
use App\Service\Wechat\MediaMook;
use Illuminate\Support\Collection;

abstract class PullingAbstracts implements PullingInterface
{
    use GetTypeTrait;

    private $count;
    private $is_mook;

    function __construct($is_mook = false)
    {
        $this->is_mook = $is_mook;
    }

    abstract function modelType();

    function mediaService()
    {
        if ($this->is_mook){
            return new MediaMook();
        }

        return new Media();
    }

    function type()
    {
        return $this->getType('Pull');
    }

    function getCount()
    {
        return $this->count;
    }

    function limit($start, $limit)
    {
        $material_service = $this->mediaService();

        $data = $material_service->limit($this->type(), $start, $limit);

        if(!$material_service->result()->isSuccess()){
            return [];
        }

        $this->count = $data->get('total_count');

        return $data->get('item') ? : [];
    }

    function model()
    {
        static $model;

        if(!$model){
            $model =  new MaterialModel();
        }

        return $model;
    }

    protected function getRowMediaId($media_id)
    {
        return $this->model()->where('mp_media_id', $media_id)->first();
    }

    function save(Collection $data)
    {
        $media_id = $data->get('media_id');

        $mdata = [
            'title'        => $data->get('name'),
            'mp_media_id'  => $media_id
        ];

        if($row = $this->getRowMediaId($media_id)){
            return $this->model()->edit($row->id, $data->all(), $mdata);
        }

        return $this->model()->add($this->modelType(), $data->all(), $mdata);
    }
}