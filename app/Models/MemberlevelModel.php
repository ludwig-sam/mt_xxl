<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberlevelModel  extends Model {

    protected $table = 'member_level';

    protected $fillable = [
        'level','exp','consume','updated_at','icon'
    ];

}

