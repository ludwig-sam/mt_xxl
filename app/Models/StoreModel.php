<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreModel extends Model
{
    use SoftDeletes;

    protected $table = 'store';


    protected $fillable = [
        'pic', 'name', 'province', 'city', 'county', 'address', 'phone', 'status', 'mch_id'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function getName($store_id)
    {
        return $this->where('id', $store_id)->value('name');
    }
}

