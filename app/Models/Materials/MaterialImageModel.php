<?php namespace App\Models\Materials;


use App\Service\Wechat\Helper\ImageHelper;
use Illuminate\Database\Eloquent\Model;

class MaterialImageModel extends Model {

    protected $table = 'wechat_material_image';

    protected $fillable = [
        'material_id', 'media_id','media_url'
    ];

    public $timestamps = false;

    public function setMediaUrlAttribute($value)
    {
        $this->attributes['media_url'] = ImageHelper::encode($value);
    }


}

