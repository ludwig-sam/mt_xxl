<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RbacUserRoleModel  extends Model {

    protected $table = 'rbac_user_role';


    protected $fillable = [
        'role_id', 'user_id', 'role_name'
    ];

    public $timestamps = false;


    public function getUserRole($user_id)
    {
        return $this->from($this->table . ' as ur')
            ->leftJoin((new RbacRoleModel())->getTable() . ' as r', 'r.id', '=', 'ur.role_id')
            ->where('ur.user_id', $user_id)
            ->select('r.name', 'ur.role_id')
            ->get();
    }

    public function permissions($userId)
    {
        return $this->from((new RbacUserRoleModel())->getTable() . ' as ur')
            ->join((new RbacRoleNodeModel())->getTable() . ' as rn', 'rn.role_id', '=', 'ur.role_id')
            ->select('rn.*')
            ->where("ur.user_id", '=', $userId)->get();
    }

    public function getNavs($userId)
    {
        return $this->from((new RbacUserRoleModel())->getTable() . ' as ur')
            ->join((new RbacRoleNodeModel())->getTable() . ' as rn', 'rn.role_id', '=', 'ur.role_id')
            ->join((new RbacNodeModel())->getTable() . ' as n', 'n.id', '=', 'rn.node_id')
            ->select('n.nav')
            ->where('n.nav', '<>', '')
            ->where("ur.user_id", '=', $userId)->get();
    }

}

