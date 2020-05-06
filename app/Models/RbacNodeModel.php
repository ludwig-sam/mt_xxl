<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RbacNodeModel  extends Model {

    protected $table = 'rbac_node';


    protected $fillable = [
        'action', 'name','pid', 'module'
    ];

    public $timestamps = false;
}

