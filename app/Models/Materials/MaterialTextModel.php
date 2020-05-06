<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialTextModel extends Model {

    protected $table = 'wechat_material_text';

    protected $fillable = [
        'material_id', 'content'
    ];

    public $timestamps = false;
}

