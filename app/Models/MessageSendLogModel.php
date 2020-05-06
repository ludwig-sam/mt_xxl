<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageSendLogModel extends Model {

    protected $table = 'message_send_log';


    protected $fillable = [
        'type', 'touser', 'content','status','comment','operator','member_operator'
    ];

    public $timestamps = false;

}

