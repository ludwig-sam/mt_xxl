<?php namespace Tests\Rbac\Providers;

use App\Service\Rbac\Contracts\PermissionInterface;
use Tests\TestCase;

class PermissionMook implements PermissionInterface {

    public function getPermissions($userId)
    {
        return [
            [
                "node_path" => "test.create",
            ],
            [
                "node_path" => "test.update"
            ]
        ];
    }
}