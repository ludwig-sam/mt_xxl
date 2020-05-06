<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsModel  extends Model {

    protected $table = 'tags';

    protected $fillable = [
        'name'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

