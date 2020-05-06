<?php namespace Tests\Lib;

use Tests\TestCase;

class Str extends TestCase{


    public function test_rand(){
        $this->assertEquals(strlen(\Libs\Str::rand(3, [1, 2])), 3);

        $this->assertEquals(strlen(\Libs\Str::rand(301)), 301);
    }

}