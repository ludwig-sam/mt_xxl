<?php namespace App\Models;

use App\Models\Traits\CallTableAble;
use Illuminate\Database\Eloquent\Model;
use App\DataTypes\PayMethodStatus;

class PayMethodModel extends Model
{

    use CallTableAble;

    protected $table = 'pay_method';


    protected $guarded = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function getConfigAttribute($value)
    {
        if (!$value) {
            return [];
        }
        return json_decode($value, true);
    }

    public function channelsList()
    {

        return $this
            ->select('name', self::f('key_name', 'channel'), 'logo')
            ->where('status', '<>', PayMethodStatus::disabled)
            ->get();
    }

    public function isAsync($id)
    {
        return $this->where('id', $id)->value('is_async');
    }

    public function getByKey($key_name)
    {
        return $this->where('key_name', $key_name)->first();
    }
}

