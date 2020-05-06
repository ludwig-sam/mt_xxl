<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GatewayNoteModel extends Model
{

    protected $table   = 'gateway_note';

    public $fillable = [
        "ip", "route", 'request', 'request_id', 'attach'
    ];

    public $timestamps = false;

    public function setAttachAttribute($value)
    {
        $this->attributes['attach'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}

