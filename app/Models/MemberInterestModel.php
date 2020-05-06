<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MemberInterestModel extends Model {

    protected $table = 'member_interest';


    protected $fillable = [
        'member_id','mch_category_id'
    ];

    public $timestamps = false;

}

