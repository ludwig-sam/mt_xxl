<?php namespace App\Models;

use App\DataTypes\MessageSendTypes;
use Illuminate\Database\Eloquent\Model;

class MessageSendConfigModel extends Model {


    public $table = 'message_send_config';

    public $fillable = [
        'remark',
        'name',
        'method',
        'param',
        'template_id'
    ];

    public $timestamps =  false;

    public function setParamAttribute($value){
        $this->attributes['param'] = $value ? json_encode($value) : '[]';
    }

    public function getParamAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getTemplateList()
    {
        return $this->where('method', MessageSendTypes::type_template)->select('id', 'name', 'remark', 'method')->get();
    }
}

