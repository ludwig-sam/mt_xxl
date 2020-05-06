<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialQrcodeModel extends Model {

    protected $table = 'wechat_material_qrcode';

    protected $fillable = [
        'material_id', 'scene_id','stype','ticket','url','expired_at','expires'
    ];

    public $timestamps = false;
}

