<?php namespace Tests\Rbac;

use App\Service\Rbac\Rbac;
use Tests\TestCase;

class RbacTest extends TestCase{

    public function test_savepermision(){
        Rbac::savePermissions('test_1', [
            'article.create','article.add'
        ]);

        $this->assertEquals(true, Rbac::check('test_1', 'article.create'));
    }


    public function test_check(){
        Rbac::savePermissions('test_1', [
            'article.create','article.add'
        ]);

        $this->assertEquals(false, Rbac::check('test_1', 'article.del'));

        $this->assertEquals(true, Rbac::check('test_1', 'article.create'));

    }


}