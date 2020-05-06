<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RbacRoleNodeModel  extends Model {

    protected $table = 'rbac_role_node';


    protected $fillable = [
        'role_id', 'node_id','node_path'
    ];

    public $timestamps = false;

}

