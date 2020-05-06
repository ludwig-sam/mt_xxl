<?php namespace App\Models;


use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;

class MemberAccountConfigModel extends Model
{

    use DynamicWhereTrait;

    protected $table = 'member_account_config';

    protected $fillable = [
        'member_id', 'pay_password'
    ];

    public $timestamps = false;


    public function getByMemberId($member_id)
    {
        return $this->where('member_id', $member_id)->first();
    }

    public function setPayPasswordAttribute($value)
    {
        $this->attributes['pay_password'] = md5($value);
    }

}

