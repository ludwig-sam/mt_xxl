<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RbacRoleModel  extends Model {

    protected $table = 'rbac_role';


    protected $fillable = [
        'name', 'remark','status'
    ];

    protected $dates = [
        'updated_at'
    ];

    protected $hidden=[
        'updated_at'
    ];

    public function  setCreatedAt($value)
    {
    }
}

