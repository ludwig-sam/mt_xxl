<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialCardModel extends Model {

    protected $table = 'wechat_material_card';

    protected $fillable = [
        'material_id', 'card_id'
    ];

    public $timestamps = false;
}

