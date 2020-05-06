<?php namespace App\Models;

use App\Models\Traits\DynamicWhereTrait;
use App\Models\Traits\FromTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminModel  extends Authenticatable {

    use Notifiable;
    use SoftDeletes;
    use  DynamicWhereTrait;
    use FromTrait;

    protected $table = 'admin';


    protected $fillable = [
        'user_name', 'password','mobile','openid','headurl','person_name','mch_id','last_login_at','status'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function qualifyColumn($column)
    {
        if (Str::contains($column, '.')) {
            return $column;
        }

        return $column;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value,PASSWORD_BCRYPT);
    }

    public function setMchIdAttribute($value)
    {
        if($value > 0){
            $this->attributes['utype'] = 'MCH_OPRATER';
        }else{
            $this->attributes['utype'] = 'ADMIN';
        }

        $this->attributes['mch_id'] = $value;
    }

    public function getLimit($limit, Collection $collection)
    {
        $defiends = [
            [
                "u.user_name",
                "like",
                "user_name"
            ],
            [
                "u.person_name",
                "like",
                "person_name"
            ],
            [
                "u.status",
                "=",
                "status"
            ]
        ];

        if(in_array($collection->get('search_by'), ['user_name', 'person_name'])) {
            $collection->offsetSet($collection->get('search_by'), $collection->get('keywords'));
        }

        $model = $this->from('u');

        $this->dynamicAnyWhere($model, $defiends, $collection);

        return $model
            ->select('u.id', 'u.user_name', 'u.person_name', 'u.mobile', 'u.status')
            ->orderBy('u.id', 'desc')
            ->groupBy('u.id')
            ->paginate($limit);
    }


}

