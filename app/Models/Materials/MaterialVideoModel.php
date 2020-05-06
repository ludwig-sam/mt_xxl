<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialVideoModel extends Model {

    protected $table = 'wechat_material_video';

    protected $fillable = [
        'material_id', 'media_id', 'media_url'
    ];

    public $timestamps = false;
}

