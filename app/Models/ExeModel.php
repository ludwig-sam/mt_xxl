<?php namespace App\Models;

use Libs\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExeModel extends Model {
	use SoftDeletes;

    protected $table = 'exe';


    protected $fillable = [
        'dev_no', 'comment','status','mch_id','store_id','card_id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function setDevNoAttribute($dev_no){
    	$this->attributes['dev_no']  =  Str::rand(15);
    }

	public function findExe($where){
		return $this->where($where)->first();
	}

	public function findWithStore($where)
    {
        return $this
            ->leftJoin((new StoreModel())->getTable() . ' as s', 's.id', '=', 'exe.store_id')
            ->where($where)
            ->select('exe.*', 's.name as store_name')
            ->first();
    }
}

