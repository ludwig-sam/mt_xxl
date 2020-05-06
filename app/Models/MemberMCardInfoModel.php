<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMCardInfoModel extends Model {

    protected $table = 'member_mcard_info';


    protected $fillable = [
        'encrypt_code','member_id'
    ];

    public $timestamps = false;

    public $primaryKey = "member_id";


    public function getEncrypt($member_id)
    {
        return $this->where('member_id', $member_id)->value('encrypt_code');
    }

    public function find($id)
    {
        return parent::where('member_id', $id)->first();
    }

}

