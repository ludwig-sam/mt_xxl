<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GatewayConfigModel extends Model
{

    protected $table   = 'gateway_config';

    public $fillable = [
        "name", "value"
    ];

    public $timestamps = false;

}

