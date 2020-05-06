<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatMenuModel extends Model {

    protected $table = 'wechat_menu';


    protected $fillable = [
        'pid', 'name', 'type','key','param','condition_id','sort'
    ];

    public $timestamps = false;


    public function setParamAttribute($value)
    {
        $this->attributes['param'] = json_encode($value);
    }

    public function getParamAttribute($value)
    {
        $param =  json_decode($value, true);

        return $param;
    }

    public function getByKey($key)
    {
        return $this->where('key', $key)->first();
    }

    public function getRow($id)
    {
        return $this->find($id);
    }

    public function countLen($condition_id, $pid)
    {
        return $this->where('pid', (int)$pid)->where('condition_id', (int)$condition_id)->count();
    }



}

