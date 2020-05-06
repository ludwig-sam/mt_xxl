<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MchCategoryModel  extends Model {
    use Notifiable;

    protected $table = 'mch_category';


    protected $fillable = [
        'name', 'pic','is_use'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $hidden = [
        'deleted_at'
    ];


}

