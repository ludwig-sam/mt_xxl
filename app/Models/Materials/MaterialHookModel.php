<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialHookModel extends Model {

    protected $table = 'wechat_material_hook';

    protected $fillable = [
        'material_id', 'name', 'is_async', 'delay'
    ];

    public $timestamps = false;
}

