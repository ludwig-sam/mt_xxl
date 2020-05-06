<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertPositionModel extends Model {

    protected $table = 'advert_position';

    protected $fillable = [
        'position_key','position_name'
    ];

	public function adverts(){
		return $this->hasMany('App\Models\AdvertModel','advert_position_id','id');
	}

}

