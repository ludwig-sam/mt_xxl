<?php namespace App\Models\Materials;


use Illuminate\Database\Eloquent\Model;

class MaterialArticleModel extends Model {

    protected $table = 'wechat_material_article';

    protected $fillable = [
        'material_id', 'articles','media_id'
    ];

    public $timestamps = false;
}

