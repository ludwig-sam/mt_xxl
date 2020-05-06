<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialVoiceModel extends Model {

    protected $table = 'wechat_material_voice';

    protected $fillable = [
        'material_id', 'media_id', 'media_url'
    ];

    public $timestamps = false;
}

