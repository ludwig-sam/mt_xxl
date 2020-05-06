<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertModel extends Model {

    protected $table = 'advertisement';


    protected $fillable = [
        'pic', 'link', 'click','sort','advert_position_id','desc'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

	public function setDescAttribute($desc){
		$this->attributes['desc'] = json_encode($desc);
	}

	public function getDescAttribute(){
		return json_decode($this->attributes['desc']);
	}

}

