<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialMusicModel extends Model {

    protected $table = 'wechat_material_music';

    protected $fillable = [
        'material_id', 'media_id', 'media_url'
    ];

    public $timestamps = false;
}

