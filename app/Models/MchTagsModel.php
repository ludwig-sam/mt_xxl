<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MchTagsModel  extends Model {
    use Notifiable;

    protected $table = 'mch_tags';

    protected $fillable = [
        'mch_id','tag_id'
    ];
    public $timestamps = false;
}

