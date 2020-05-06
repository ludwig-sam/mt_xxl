<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ReplyMaterialModel extends Model {

    protected $table = 'wechat_reply_material';

    public $timestamps = false;

    protected $fillable = [
        'reply_id' , 'material_id', 'condition_key', 'condition_op', 'condition_val'
    ];

    public function hasMaterial(){
    	return $this->belongsTo(MaterialModel::class,'material_id','id');
    }

}

