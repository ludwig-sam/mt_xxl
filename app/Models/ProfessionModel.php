<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionModel extends Model {

    protected $table = 'profession';

    protected $guarded = [
        "id"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
	    'created_at', 'updated_at','deleted_at'
    ];

}

