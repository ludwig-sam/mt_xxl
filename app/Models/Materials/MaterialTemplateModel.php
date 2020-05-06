<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialTemplateModel extends Model {

    protected $table = 'wechat_material_template';

    protected $fillable = [
        'material_id', 'template_id', 'param', 'miniprogram_pagepath'
    ];

    public $timestamps = false;

    public function setParamAttribute($value){
        $this->attributes['param'] = $value ? json_encode($value) : '[]';
    }

    public function getParamAttribute($value)
    {
        return json_decode($value, true);
    }


}

