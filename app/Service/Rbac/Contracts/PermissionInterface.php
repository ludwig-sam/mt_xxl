<?php namespace App\Service\Rbac\Contracts;

interface PermissionInterface{
    function getPermissions($userId);
}