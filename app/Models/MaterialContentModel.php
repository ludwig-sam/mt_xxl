<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MaterialContentModel extends Model {

    protected $table = 'wechat_material_';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'id'
    ];

}

