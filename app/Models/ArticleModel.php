<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model {

    protected $table = 'article';


    protected $fillable = [
        'title', 'introduce', 'content','user_id'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];


}

