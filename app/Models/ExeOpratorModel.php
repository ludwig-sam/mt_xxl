<?php namespace App\Models;

use Libs\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExeOpratorModel extends Model {
use SoftDeletes;

    protected $table = 'exe_oprator';


    protected $fillable = [
        'username', 'mobile', 'id_card','status','mch_id','store_id','headurl','password'
    ];

    protected $dates = [
        'created_at', 'updated_at','deleted_at'
    ];

    protected $hidden = [
        'deleted_at','password'
    ];

    public function getByUserName($userName){
        return $this->where(["username" => $userName])->first();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value,PASSWORD_BCRYPT);
    }

    public function loginSuccess(){
        $this->last_login_at = Time::date();
        return $this->save();
    }
}

