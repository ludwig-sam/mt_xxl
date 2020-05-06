<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayOrderDetailModel extends Model {

    protected $table = 'pay_order_detail';

    protected $fillable = [
        "order_id","body","status_msg","coupon_id","coupon_code","point","exp","balance","openid",'member_level'
    ];

    public $timestamps = false;

    protected $primaryKey = "order_id";


    public function getFill($data)
    {
        $result = [];

        $fillable = $this->fillable ? : $this->getAttributes();

        foreach ($fillable as $name){
            if(isset($data[$name]))$result[$name] = $data[$name];
        }

        return $result;
    }

    public function getByOrderId($oder_id)
    {
        return $this->where('order_id', $oder_id)->first();
    }

}

