<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OprationLogModel  extends Model {


    protected $table = 'opration_log';


    protected $fillable = [
        'title', 'detial','user_id'
    ];

	protected $dates = [
		'created_at'
	];

	public function setUpdatedAt($value){
	}

	public function exportFromLimit($ids, $fields){
		return $this->from($this->table . ' as o')
			->leftJoin('admin as a',"o.user_id","=","a.id")
			->select($fields)
			->whereIn('o.id',$ids)
			->orderBy('id','desc')
			->get();
	}
}

