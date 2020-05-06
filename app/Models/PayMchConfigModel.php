<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayMchConfigModel extends Model {

    protected $table = 'pay_mch_config';


    protected $fillable = [
        'payment_way', 'mch_id' ,'config_param'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function getConfigParamAttribute($key)
    {
        return json_decode($key, true);
    }

}

