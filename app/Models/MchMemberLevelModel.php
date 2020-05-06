<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MchMemberLevelModel  extends Model {

    protected $table = 'mch_member_level';

    protected $hidden = [
        'mch_id'
    ];
    public $timestamps = false;

}

