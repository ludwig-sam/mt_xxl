<?php namespace Tests\Rbac;

use App\Service\Rbac\Rbac;
use Tests\Rbac\Providers\PermissionMook;
use Tests\TestCase;

class RbacExpireTest extends TestCase{

    public function test_expire(){

        $rbac = new Rbac(new PermissionMook(), 1);

        $rbac->savePermissions('test_1', [
            'article.create','article.add'
        ]);

        usleep(1000000);

        $this->assertEquals(false, $rbac->check('test_1', 'article.create'));
    }

    public function test_loginExpire(){
        $rbac = new Rbac(new PermissionMook(), 1);

        $rbac->login(1);

        usleep(1000000);

        $this->assertEquals(true, $rbac->check(1, 'test.create'));
    }


}