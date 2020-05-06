<?php namespace App\Service\Wechat;


use App\Service\Wechat\Contracts\MediaInterface;
use App\Service\Wechat\Mook\MediaLimitMook;
use Illuminate\Support\Str;

class MediaMook extends Wechat implements MediaInterface {


    public function limit($type, $start, $number = 20)
    {
        $material_mook  = new MediaLimitMook();
        $method         = 'getLimit' . Str::studly($type);
        $ret            = $material_mook->$method($start, $number);

        return $this->parseResult($ret)->getData();
    }



}