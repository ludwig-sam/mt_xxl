<?php namespace App\Service\Rbac;


use App\Models\AdminModel;
use App\Models\RbacUserRoleModel;
use App\Service\Rbac\Contracts\PermissionInterface;

class PermissionStack implements PermissionInterface {

    private $model;

    public function __construct()
    {
        $this->model = new RbacUserRoleModel();
    }

    public function getPermissions($userId)
    {
        return $this->model->permissions($userId)->toArray();
    }
}